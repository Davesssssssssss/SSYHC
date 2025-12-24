import wixData from "wix-data";
import { session } from "wix-storage-frontend";
let fname = ''
let lname = ''
let workexp = ''
let phone =  ''
$w.onReady(function () {
    
    let data = session.getItem("loggedUser"); 
    let jobdata = session.getItem("jobposition"); 
    
    let parsedData = JSON.parse(data);
    let jobparsedData = JSON.parse(jobdata);
     fname = parsedData.fname
     lname = parsedData.lname
     workexp = parsedData.workExp
     phone = parsedData.phone
    // Optional: If you have a Wix Image element on the page outside the iframe,
    // you can set its source here. Otherwise, this line can be removed.
    // $w("#imageX16").src = parsedData.formalID; 

    console.log("Logged User Data:", parsedData);
    console.log("Job Position Data:", jobparsedData);

    if (parsedData.role === "caregiver") { // Use strict equality
        // Hide/Show elements based on role
        $w('#horizontalMenu1').hide();
        $w('#box28').hide();
        $w('#CLIENTBTN').hide();
        $w('#logout').show();
        
        // Update elements on the Wix page directly
        $w('#fullname').text = parsedData.fullName; // Assuming #fullname is a text element on your Wix page

        // Send initial data to iframe by constructing the object directly in postMessage
        $w("#html1").postMessage({
            fName: fname, 
             lName: lname,
             workExp: workexp,
             phones: phone,
            // username: parsedData.email,
            // zipcode: parsedData.zipCode,
            // address: parsedData.address,
            // fullName: parsedData.fullName,
            // prefered: parsedData.preferredSchedule,
            // availability: parsedData.availability,
            // profilePicUrl: parsedData.profilePicture,
            
            // // Documents
            // resumeUrl: parsedData.resumeDocumentUrl,
            // validIDUrl: parsedData.formalID, // Sending the formalID URL

            // // Job data
            // job: jobparsedData.jobTitle,
            // id: jobparsedData.id
        });
        
        console.log("Data sent to iframe."); // Log that data was sent
        
        // Listen for messages from iframe (e.g., for creating a new item)
        $w("#html1").onMessage((event) => {
            const { action, data } = event.data;

            if (action === "create") {
                createItem(data);
            }
        });
    } else {
        console.log("User is not a caregiver. Redirecting or displaying different content.");
    }
});

// CREATE function (logs and inserts to "LIST_APPLCANTS" collection)
function createItem(data) {
    console.log("📝 Received applicant data for creation:", data);
     wixData.insert("LIST_APPLCANTS", data)
        .then((saved) => {
            console.log("✅ Application saved:", saved);
            $w("#html1").postMessage({ action: "success", message: "Application submitted successfully!", item: saved });
        })
        .catch(err => {
            console.error("❌ Insert error:", err);
            $w("#html1").postMessage({ action: "error", message: "Something went wrong. Please try again." });
        });
}