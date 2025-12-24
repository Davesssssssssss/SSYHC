import wixData from "wix-data";// Velo API Reference: https://www.wix.com/velo/reference/api-overview/introductionackend";
import wixLocationFrontend from "wix-location-frontend";
    let uploadedValidIDUrl = "";
let uploadedFormalIDUrl = "";
let  xrayIDUrl = "";
let fingerprintIDUrl = "";
let user_id = '';
$w.onReady(function () {
  // $w("#imageX17").src = "wix:image://v1/7339e9_14a1e96fe687453d95320c811534ecb8~mv2.png/n2.PNG#originWidth=429&originHeight=272";
   $w('#uploadButton1').hide()
   $w('#uploadButton2').hide()
    $w('#uploadButton3').hide()
	 $w('#uploadButton4').hide()
	  $w('#imageX16').hide()
   $w('#imageX17').hide()
    $w('#imageX18').hide()
	 $w('#imageX19').hide()
   $w('#horizontalMenu1').hide()
   $w('#box28').hide()
	// Write your Javascript code here using the Velo framework API
  $w('#fullname').hide()
       $w('#logout').hide()
         $w('#CLIENTBTN').hide()
        $w('#button9').hide()
	// Print hello world:
	// console.log("Hello world!");                   

	// Call functions on page elements, e.g.:
	// $w("#button1").label = "Click me!";

	// Click "Run", or Preview your site, to execute your 
	
	  $w("#html4").onMessage((event) => {
        const { action, data } = event.data;
        console.log("Message from iframe:", action, data);

        if (action === "create") {
            createItem(data);
              console.log(data.action);
        }
        else if (action === "showButton") {
            // updateItem(data);
         $w('#uploadButton1').show() // formal
          $w('#uploadButton2').show() // validid
          $w('#uploadButton3').show() // formal
          $w('#uploadButton4').show() // validid
  $w('#imageX16').show() // formal
          $w('#imageX17').show() // validid
          $w('#imageX18').show() // formal
          $w('#imageX19').show() // validid
        }else if (action === "showalert") {
          $w("#html4").postMessage({ action: "doshowAlert" });

        }else if(action === "SearchZipcode"){
               wixData.query("CRG_INFO")
        .eq("zipcode",data.usZipcode)
        .count()
        .then((total) => {
            console.log("Total results:", total);
            // you can display it in a text element
            // $w("#totalResultsText").text = `Total results: ${total}`;
            let iframe = $w("#html4"); // iframe element on your Wix page
            iframe.postMessage({ type: "zipcodeResult", total: total });
        })
        .catch((err) => {
            console.error("Query failed:", err);
        });
          }else if (action === "ToLogin") {
          wixLocationFrontend.to("/login");
        }
       
    });

    // Send initial data to iframe on load
    // loadData().then(items => {
    //     $w("#html1").postMessage({ action: "load", items });
    // });
})

//     $w("#uploadButton1").onChange(() => {
//     const files = $w("#uploadButton1").value;

//     if (files.length > 0) {
//       console.log("Uploading file...");

//       $w("#uploadButton1").uploadFiles()
//         .then((uploadedFiles) => {
//           if (uploadedFiles.length > 0) {
//             const uploadedFile = uploadedFiles[0]; // Assuming only one file is uploaded
//             uploadedFormalIDUrl = uploadedFile.fileUrl;

//             console.log("Image uploaded to:", uploadedFormalIDUrl);

//             // Show the image in preview
//             // $w("#image45").src = uploadedFormalIDUrl;
//             // $w("#image45").show();
//           }
//         })
//         .catch((error) => {
//           console.error("Upload failed:", error.errorDescription);
//         });
//     }
// $w("#uploadButton1").onChange(() => {
//   const files = $w("#uploadButton1").value;

//   if (files.length > 0) {
//     console.log("Uploading file...");

//     $w("#uploadButton1").uploadFiles()
//       .then((uploadedFiles) => {
//         if (uploadedFiles.length > 0) {
//           const uploadedFile = uploadedFiles[0]; 
//           const fileUrl = uploadedFile.fileUrl; // wix:image://v1/...
//           console.log("Raw URL:", fileUrl);

//           // Convert wix:image:// → static.wixstatic.com
//            uploadedFormalIDUrl =  fileUrl
//           console.log("Clean static URL:", uploadedFormalIDUrl);

//           // Example: show in image
//            $w("#imageX16").src =  fileUrl;
//           // $w("#image45").show();
//         }
//       })
//       .catch((error) => {
//         console.error("Upload failed:", error.errorDescription);
//       });
//   }
// });
$w("#uploadButton1").onChange(() => {
  const files = $w("#uploadButton1").value;

  if (files.length > 0) {
    console.log("Uploading file...");

    $w("#uploadButton1").uploadFiles()
      .then((uploadedFiles) => {
        if (uploadedFiles.length > 0) {
          const uploadedFile = uploadedFiles[0]; 
          const fileUrl = uploadedFile.fileUrl; // wix:image://v1/...

          console.log("Raw URL:", fileUrl);

          // Extract the file name from the wix:image:// URL
          const matches = fileUrl.match(/wix:image:\/\/v1\/([^\/]+)/);
        const extractedFileName = matches ? matches[1] : null;

          // if (extractedFileName) {
          //   console.log("Extracted File Name:", extractedFileName);
          // } else {
          //   console.log("Failed to extract file name.");
          // }
         
               uploadedValidIDUrl = "https://static.wixstatic.com/media/" + extractedFileName;
          // Optional: show image using original URL
          $w("#imageX16").src = fileUrl;
        }
      })
      .catch((error) => {
        console.error("Upload failed:", error.errorDescription);
      });
  }
});

$w("#uploadButton2").onChange(() => {
  const files = $w("#uploadButton2").value;

  if (files.length > 0) {
    console.log("Uploading file...");

    $w("#uploadButton2").uploadFiles()
      .then((uploadedFiles) => {
        if (uploadedFiles.length > 0) {
          const uploadedFile = uploadedFiles[0]; 
          const fileUrl = uploadedFile.fileUrl; // wix:image://v1/...

          console.log("Raw URL:", fileUrl);

          // Extract the file name from the wix:image:// URL
          const matches = fileUrl.match(/wix:image:\/\/v1\/([^\/]+)/);
        const extractedFileName = matches ? matches[1] : null;

          // if (extractedFileName) {
          //   console.log("Extracted File Name:", extractedFileName);
          // } else {
          //   console.log("Failed to extract file name.");
          // }
            uploadedFormalIDUrl = "https://static.wixstatic.com/media/" + extractedFileName;
          // Optional: show image using original URL
          $w("#imageX17").src = fileUrl;
        }
      })
      .catch((error) => {
        console.error("Upload failed:", error.errorDescription);
      });
  }
});

$w("#uploadButton3").onChange(() => {
  const files = $w("#uploadButton3").value;

  if (files.length > 0) {
    console.log("Uploading file...");

    $w("#uploadButton3").uploadFiles()
      .then((uploadedFiles) => {
        if (uploadedFiles.length > 0) {
          const uploadedFile = uploadedFiles[0]; 
          const fileUrl = uploadedFile.fileUrl; // wix:image://v1/...

          console.log("Raw URL:", fileUrl);

          // Extract the file name from the wix:image:// URL
          const matches = fileUrl.match(/wix:image:\/\/v1\/([^\/]+)/);
        const extractedFileName = matches ? matches[1] : null;

          // if (extractedFileName) {
          //   console.log("Extracted File Name:", extractedFileName);
          // } else {
          //   console.log("Failed to extract file name.");
          // }
             xrayIDUrl = "https://static.wixstatic.com/media/" + extractedFileName;
          // Optional: show image using original URL
          $w("#imageX18").src = fileUrl;
        }
      })
      .catch((error) => {
        console.error("Upload failed:", error.errorDescription);
      });
  }
});

$w("#uploadButton4").onChange(() => {
  const files = $w("#uploadButton4").value;

  if (files.length > 0) {
    console.log("Uploading file...");

    $w("#uploadButton4").uploadFiles()
      .then((uploadedFiles) => {
        if (uploadedFiles.length > 0) {
          const uploadedFile = uploadedFiles[0]; 
          const fileUrl = uploadedFile.fileUrl; // wix:image://v1/...

          console.log("Raw URL:", fileUrl);

          // Extract the file name from the wix:image:// URL
          const matches = fileUrl.match(/wix:image:\/\/v1\/([^\/]+)/);
        const extractedFileName = matches ? matches[1] : null;

          // if (extractedFileName) {
          //   console.log("Extracted File Name:", extractedFileName);
          // } else {
          //   console.log("Failed to extract file name.");
          // }
             fingerprintIDUrl = "https://static.wixstatic.com/media/" + extractedFileName;
          // Optional: show image using original URL
          $w("#imageX19").src = fileUrl;
        }
      })
      .catch((error) => {
        console.error("Upload failed:", error.errorDescription);
      });
  }
});
// CREATE fasync unction 
// function createItem(data) {

//     const currentYear = new Date().getFullYear(); // e.g., 2025

// 	let  itemToInsert = {
// // firstname: data.fname, 
// job_role: data.jobposition,
// user_role: data.jobposition,
// firstname:  data.fname,
// lastname: data.lname,
// middlename: data.mname,
// suffix: data.suffix,
// gender: data.gender,
// address: data.useraddress,
// zipcode: data.usZipcode,
// expercience: data.experience,
// reference: data.reference,
// phone: data.phone,
// email: data.email,
// password: data.password,
// talents: data.talents,
// skills: data.skills,
// hca: data.hca,
// prefered: data.PreferedSchd,
// availability: data.AvailableSched,
// // vaildId: data.formalFile,
// formalid:   uploadedFormalIDUrl ,
// userid: currentYear,

// 	}
//     wixData.insert("CRG_INFO", itemToInsert)
//         .then(() => {
//             console.log("Item created in CLIENT_INFO");
            
//             //  wixLocationFrontend.to("/login");
//         })
//         .then(items => {
//             $w("#html4").postMessage({ action: "load", items });
//         })
//         .catch(err => console.error(err));
// }
function createItem(data) {
    const currentYear = new Date().getFullYear(); // e.g., 2025
    const baseUserId = currentYear*100000;      // 202500000
let newUserId;
    // Step 1: Get latest userid from collection
    wixData.query("CRG_INFO")
        .descending("userid")
        .limit(1)
        .find()
        .then((results) => {
            

            if (results.items.length > 0) {
                // Existing entries found, increment the last userid
                newUserId = results.items[0].userid + 1;
                console.log(results.items[0].userid);
            } else {
                // No entries yet, start from baseUserId + 1
                newUserId = baseUserId + 1;
            }

            // Step 2: Build the item
            let itemToInsert = {
                job_role: data.jobposition,
                user_role: "caregiver",
                firstname: data.fname,
                lastname: data.lname,
                middlename: data.mname,
                suffix: data.suffix,
                gender: data.gender,
                address: data.useraddress,
                zipcode: data.usZipcode,
                expercience: data.workexp,
                reference: data.reference,
                phone: data.phone,
                email: data.email,
                password: data.password,
                talents: data.talents,
                skills: data.skills,
                hca: data.hca,
                prefered: data.PreferedSchd,
                availability: data.AvailableSched,
                formalid: uploadedFormalIDUrl,
                validid:  uploadedValidIDUrl,
                xray: xrayIDUrl,
                fingerPrint: fingerprintIDUrl,
                userid: newUserId,
              

            };

            // Step 3: Insert the item
            return wixData.insert("CRG_INFO", itemToInsert);
        })
        .then(() => {
            console.log("Item created in CRG_INFO");
            // Optional: redirect or show confirmation
        })
        .catch((err) => {
            console.error("Error creating user:", err);
        });
}




