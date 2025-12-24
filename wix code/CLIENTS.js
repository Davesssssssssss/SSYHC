import wixData from "wix-data";
import { session } from "wix-storage-frontend";

import wixLocationFrontend from "wix-location-frontend";
$w.onReady(function () {
  $w('#horizontalMenu1').hide();
  $w('#box28').hide();
  $w('#button9').hide();

  let data = session.getItem("loggedUser");

  if (!data) return;

  let parsedData = JSON.parse(data);

  if (parsedData.role === "caregiver") {
    $w('#logout').show();
    $w('#fullname').text = parsedData.fullName;

    // Listen for messages from the iframe
    $w("#html4").onMessage((event) => {
      const { action, data } = event.data;
      console.log("Message from iframe:", action, data);

      if (action === "create") {
        createItem(data);
      } else if (action === "update") {
        updateItem(data);
      } else if (action === "apply") {
        applyJobsItem(data);
      }
    });

    // Send initial data to the iframe
    loadData().then(items => {
      $w("#html4").postMessage({ action: "load", items });
    });
  }
});

// Load all job data
// function loadData() {
//   return wixData.query("JOB_POSTED").find()
//     .then(res => res.items);
// }
function loadData() {
  let data = session.getItem("loggedUser");
  let parsedData = JSON.parse(data);
  let userid = parsedData.userid
  return wixData.query("CRG_INFO")
   
    .find()
    .then(res => res.items)
    .catch(err => console.error("Error loading data:", err));
}

function  applyJobsItem(data){
 const jobData = {
    jobid: data.jobid,
    clientid: data.clientid
  };

  // Save to session
  session.setItem("applyjobs", JSON.stringify(jobData));
         wixLocationFrontend.to("/applyjobs-new");
   
}

// Create new job
function createItem(data) {
  wixData.insert("JOB_POSTED", data)
    .then(() => loadData())
    .then(items => {
      $w("#html4").postMessage({ action: "load", items });
    })
    .catch(err => console.error("Create failed:", err));
}

// Update existing job
function updateItem(data) {
  wixData.get("JOB_POSTED", data._id)
    .then(item => {
      item.job_title = data.job_title;
      item.location = data.location;
      item.type = data.type;
      item.pay_rate = data.pay_rate;
      item.job_description = data.job_description;

      return wixData.update("JOB_POSTED", item);
    })
    .then(() => loadData())
    .then(items => {
      $w("#html4").postMessage({ action: "load", items });
    })
    .catch(err => console.error("Update failed:", err));
}

// Delete job
function deleteItem(data) {
  wixData.remove("JOB_POSTED", data._id)
    .then(() => loadData())
    .then(items => {
      $w("#html4").postMessage({ action: "load", items });
    })
    .catch(err => console.error("Delete failed:", err));
}
