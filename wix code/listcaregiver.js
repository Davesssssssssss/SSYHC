// Filename: <your-page-name>.js (Wix Velo backend)

import wixData from "wix-data";
import { session } from "wix-storage-frontend";
import wixLocationFrontend from "wix-location-frontend";

// Define the number of items per page
const CLIENTS_PER_PAGE = 5; // <--- This is your pagination limit

$w.onReady(function () {
  console.log("Wix page is ready.");

  $w('#horizontalMenu1').hide();
  $w('#box28').hide();
  $w('#button9').hide();

  let loggedUserData = session.getItem("loggedUser");

  if (!loggedUserData) {
    console.log("No logged user data found in session. User might not be logged in.");
    return;
  }

  let parsedUserData;
  try {
    parsedUserData = JSON.parse(loggedUserData);
    console.log("Logged user data parsed:", parsedUserData);
  } catch (error) {
    console.error("Error parsing logged user data from session:", error);
    return;
  }

  if (parsedUserData.role === "caregiver") {
    console.log("User is a caregiver. Initializing client management.");
    $w('#logout').show();
    $w('#fullname').text = parsedUserData.fullName;

    // --- Message Listener for HTML Component (#html4) ---
    $w("#html4").onMessage((event) => {
      const { action, data } = event.data;
      console.log(`Received message from iframe. Action: ${action}, Data:`, data);

      switch (action) {
        case "createClient":
          createClientItem(data);
          break;
        case "updateClient":
          updateClientItem(data);
          break;
        case "deleteClient":
          deleteClientItem(data);
          break;
        case "apply":
          applyJobsItem(data);
          break;
        case "loadPage": // New action to load a specific page
          // 'data' will contain { page: <pageNumber> }
          console.log(`Request to load page ${data.page}`);
          refreshAndPostClientData(data.page); // Pass requested page number
          break;
        default:
          console.warn(`Unknown action received from iframe: ${action}`);
      }
    });

    // --- Initial Data Load to HTML Component (#html4) ---
    // Load the first page when the Wix page loads
    refreshAndPostClientData(1); // Load page 1 initially

  } else {
    console.log("User role is not 'caregiver'. Access to client management denied.");
  }
});

// --- Helper function to refresh and post updated client data to the iframe ---
// Now accepts a 'page' number to load
async function refreshAndPostClientData(page = 1) { // Default to page 1
  console.log(`Refreshing client data and sending page ${page} to iframe...`);
  try {
    const totalCount = await getTotalClientCount();
    const totalPages = Math.ceil(totalCount / CLIENTS_PER_PAGE);
    const clients = await loadClientData(page, CLIENTS_PER_PAGE);

    $w("#html4").postMessage({
      action: "loadClients",
      items: clients,
      currentPage: page,
      totalPages: totalPages,
      totalClients: totalCount
    });
    console.log(`Client data (Page ${page} of ${totalPages}) refreshed and posted to iframe.`);
  } catch (err) {
    console.error("Error refreshing and posting client data with pagination:", err);
    $w("#html4").postMessage({
      action: "loadClients",
      items: [],
      currentPage: 1,
      totalPages: 1,
      totalClients: 0,
      error: err.message
    });
  }
}

// --- CLIENT MANAGEMENT FUNCTIONS (for CRG_INFO collection) ---

/**
 * Loads a specific page of client data from the 'CRG_INFO' collection.
 * @param {number} page - The page number to load (1-indexed).
 * @param {number} limit - The number of items per page.
 * @returns {Promise<Array>} A promise that resolves to an array of client items for the requested page.
 */
async function loadClientData(page, limit) {
  const skip = (page - 1) * limit;
  console.log(`Querying CRG_INFO collection: skip=${skip}, limit=${limit}`);
  try {
    const res = await wixData.query("CRG_INFO")
      .skip(skip)
      .limit(limit)
      .eq("user_role","caregiver")
      .find();
    console.log(`Found ${res.items.length} client items for page ${page}.`);
    return res.items;
  } catch (err) {
    console.error("Error loading client data from CRG_INFO with pagination:", err);
    return [];
  }
}

/**
 * Gets the total count of clients in the 'CRG_INFO' collection.
 * @returns {Promise<number>} A promise that resolves to the total number of clients.
 */
async function getTotalClientCount() {
  console.log("Getting total client count from CRG_INFO...");
  try {
    const res = await wixData.query("CRG_INFO").count();
    console.log(`Total client count: ${res}`);
    return res;
  } catch (err) {
    console.error("Error getting total client count:", err);
    return 0; // Return 0 on error
  }
}

/**
 * Creates a new client item in the 'CRG_INFO' collection.
 * @param {Object} data - The client data to insert (e.g., { fullName: "...", contacts: "..." }).
 */
async function createClientItem(data) {
  console.log("Attempting to create new client with data:", data);
  try {
    const result = await wixData.insert("CRG_INFO", data);
    console.log("Client created successfully:", result);
    // After creating, re-load the first page (or stay on current and re-calculate)
    refreshAndPostClientData(1); // Go back to page 1 to see the new item
  } catch (err) {
    console.error("Create client failed:", err);
  }
}

/**
 * Updates an existing client item in the 'CRG_INFO' collection.
 * @param {Object} data - The client data including '_id' and updated fields.
 * @param {number} currentPage - The current page number to return to after update.
 */
async function updateClientItem(data, currentPage = 1) { // Added currentPage for refresh
  console.log(`Attempting to update client with _id: ${data._id}, new data:`, data);
  try {
    const item = await wixData.get("CRG_INFO", data._id);
    if (!item) {
      console.error(`Client with _id ${data._id} not found for update.`);
      return;
    }

    item.firstname = data.fName;
     item.lastname = data.lName;
    item.phone = data.phone;
    item.email = data.email;
    item.zipcode = data.zipcode;

    const result = await wixData.update("CRG_INFO", item);
    console.log("Client updated successfully:", result);
    refreshAndPostClientData(currentPage); // Refresh UI on the current page
  } catch (err) {
    console.error(`Update client failed for _id ${data._id}:`, err);
  }
}

/**
 * Deletes a client item from the 'CRG_INFO' collection.
 * @param {Object} data - An object containing the '_id' of the client to delete.
 * @param {number} currentPage - The current page number to return to after delete.
 */
async function deleteClientItem(data, currentPage = 1) { // Added currentPage for refresh
  console.log(`Attempting to delete client with _id: ${data._id}`);
  try {
    const result = await wixData.remove("CRG_INFO", data._id);
    console.log("Client deleted successfully:", result);
    // After deleting, check if the current page still has items, otherwise go back
    const totalCount = await getTotalClientCount();
    const totalPages = Math.ceil(totalCount / CLIENTS_PER_PAGE);
    const newPage = (currentPage > totalPages && totalPages > 0) ? totalPages : currentPage;
    refreshAndPostClientData(newPage); // Refresh UI, potentially on a previous page
  } catch (err) {
    console.error(`Delete client failed for _id ${data._id}:`, err);
  }
}

// --- ORIGINAL JOB-RELATED FUNCTIONS ---
function applyJobsItem(data){
  console.log("Applying for job with data:", data);
  const jobApplicationData = {
    jobid: data.jobid,
    clientid: data.clientid
  };
  session.setItem("applyjobs", JSON.stringify(jobApplicationData));
  wixLocationFrontend.to("/applyjobs-new");
}