
    // import wixData from 'wix-data';

// $w.onReady(function () {
//   // Listen for messages from iframe
//   $w("#html1").onMessage((event) => {
//     const { action, data } = event.data;
//     console.log("Message from iframe:", action, data);

//     if (action === "create") {
//       createItem(data);
//     }
//   });

//   // Send job data to iframe
//   loadData().then(items => {
//     $w("#html1").postMessage({ action: "load", items });
//   });
// });

// function loadData() {
//   return wixData.query("JOB_POSTED").find()
//     .then(res => res.items);
//     console.log(loadData());
// }

// function createItem(data) {
//   wixData.insert("JOB_POSTED", data)
//     .then(() => loadData())
//     .then(items => {
//       $w("#html1").postMessage({ action: "load", items });
//     })
//     .catch(err => console.error(err));
// }
import wixData from "wix-data";
  import wixLocationFrontend from "wix-location-frontend";

 import { session } from "wix-storage-frontend";
$w.onReady(function () {
        let data = session.getItem("loggedUser"); // "value
 

   let parsedData = JSON.parse(data);
   $w("#html1").postMessage(parsedData);
   console.log(parsedData);
 if (parsedData.role == "caregiver") {
      $w('#CLIENTBTN').hide()
      $w('#horizontalMenu1').hide()
       $w('#box28').hide()
        $w('#logout').show()
        $w('#fullname').text = parsedData.fullName;
       $w('#fullname').show()
    // Listen for messages from the HTML iframe
    $w("#html1").onMessage((event) => {
        const { action, data } = event.data;
        console.log("Message from iframe:", action, data);

        if (action === "create") {
            createItem(data);
        } else if (action === "update") {
            updateItem(data);
        } else if (action === "delete") {
            deleteItem(data);
        }
         else if (action == "send") {
              redirectPage(data);
            console.log(action);
         }
         else{
            console.log("error");
         }
    });

    // Load initial data and send to iframe
    loadData().then(items => {
        $w("#html1").postMessage({ action: "load", items });
    });
}
});

// REDIRECT PAGE
function  redirectPage(data){
    // wixLocationFrontend.to("/applyjobs");
// session.clear();
   let sendData = JSON.stringify(data)
     let sendDatas = JSON.parse(sendData)
//   console.log(data);
console.log("connectedttttttt" + JSON.stringify(sendDatas));
   session.setItem("jobposition", JSON.stringify({
                         
                            jobTitle: sendDatas.jobTitle,
                             id: sendDatas.id,
                             owner: sendDatas.owner
                        }));

console.log(sendDatas.owner);

 
}
// CREATE
 let jobdata = session.getItem("jobposition"); 
console.log("connectedssss" + jobdata);
function createItem(data) {
    wixData.insert("JOB_POSTED", data)
        .then(() => {
            console.log("Item created in CLIENT_INFO");
            return loadData();
        })
        .then(items => {
            $w("#html1").postMessage({ action: "load", items });
        })
        .catch(err => console.error("Create failed:", err));
}

// UPDATE
function updateItem(data) {
    wixData.get("JOB_POSTED", data._id)
        .then(item => {
            // Update the item fields
            item.job_title = data.job_title;
            item.location = data.location;
            item.type = data.type;
            item.pay_rate = data.pay_rate;
            item.job_description = data.job_description;

            return wixData.update("JOB_POSTED", item);
        })
        .then(() => {
            console.log();
            return loadData();
        })
        .then(items => {
            $w("#html1").postMessage({ action: "load", items });
        })
        .catch(err => console.error("Update failed:", err));
}

// DELETE
function deleteItem(data) {
    wixData.remove("JOB_POSTED", data._id)
        .then(() => {
            console.log("Item deleted from CLIENT_INFO");
            return loadData();
        })
        .then(items => {
            $w("#html1").postMessage({ action: "load", items });
        })
        .catch(err => console.error("Delete failed:", err));
}

// READ
function loadData() {
    return wixData.query("JOB_POSTED").find()
        .then(res => res.items);
}

