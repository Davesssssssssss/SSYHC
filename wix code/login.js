import wixData from "wix-data";

import wixLocationFrontend from "wix-location-frontend";


import { session } from "wix-storage-frontend";
$w.onReady(function () {
     $w('#fullname').hide()
       $w('#logout').hide()
         $w('#CLIENTBTN').hide()
        $w('#button9').hide()
    $w("#html1").onMessage((event) => {
        let msg = event.data;

        if (msg.action === "login") {
            let { username, password } = msg.data;

            wixData.query("CRG_INFO")
                .eq("email", username)
                .eq("password", password)
                .find()
                .then((results) => {
                    if (results.items.length > 0) {
                        let user = results.items[0];

                        // Save user to session
                        session.setItem("loggedUser", JSON.stringify({
                            id: user._id,
                            username: user.email,
                            fullName: user.firstname + user.lastname,
                            role: user.user_role,
                            phone: user.phone,
                            zipcode: user.zipcode,
                            address: user.address,
                            workExp: user.expercience,
                            refer: user.reference,
                            talents:  user.talents,
                            skills:  user.skills,
                            hca: user.hca,
                            prefereds: user.preferred,
                            avail: user.availability,
                            validID: user.vaildId,
                            formalID: user.formalId,
                            fname:  user.firstname,
                            lname: user.lastname,
                            suffix: user.suffix,
                            middlename: user.middlename,
                            prefered: user.prefered,
                            availability: user.availability,

                            
                        }));
                     
      let data = session.getItem("loggedUser"); // "value


   let parsedData = JSON.parse(data);
   if (parsedData.role == "client") {
	wixLocationFrontend.to("/jobposting");
   }else if(parsedData.role == "caregiver"){
    	wixLocationFrontend.to("/findjobs");
   }
console.log(parsedData.role);
                        // ✅ Console log when user is logged in
                        console.log(user.firstname);

                        // Send success back to HTML
                        $w("#html1").postMessage({
                            action: "loginResult",
                            success: true,
                            user: {
                                id: user._id,
                                username: user.email,
                                fullName: user.firstname || user.email,
                                 role: user.user_role
                            }
                        });
                    } else {
                        $w("#html1").postMessage({
                            action: "loginResult",
                            success: false
                        });
                    }
                })
                .catch((err) => {
                    console.error("Login error:", err);
                    $w("#html1").postMessage({
                        action: "loginResult",
                        success: false
                    });
                });
        }

        if (msg.action === "create") {
            let userData = JSON.parse(session.getItem("loggedUser ") || "{}");
            let item = {
                title: msg.data.title,
                description: msg.data.description,
                userId: userData.id,
                userName: userData.fullName
            };

            wixData.insert("YourCrudCollection", item)
                .then(() => $w("#html1").postMessage({ action: "createResult", success: true }))
                .catch((err) => {
                    console.error("Create error:", err);
                    $w("#html1").postMessage({ action: "createResult", success: false });
                });
        }

        if (msg.action === "update") {
            wixData.update("YourCrudCollection", msg.data)
                .then(() => $w("#html1").postMessage({ action: "updateResult", success: true }))
                .catch((err) => {
                    console.error("Update error:", err);
                    $w("#html1").postMessage({ action: "updateResult", success: false });
                });
        }

        if (msg.action === "delete") {
            wixData.remove("YourCrudCollection", msg.data.id)
                .then(() => $w("#html1").postMessage({ action: "deleteResult", success: true }))
                .catch((err) => {
                    console.error("Delete error:", err);
                    $w("#html1").postMessage({ action: "deleteResult", success: false });
                });
        }

        if (msg.action === "load") {
            let userData = JSON.parse(session.getItem("loggedUser ") || "{}");
            wixData.query("YourCrudCollection")
                .eq("userId", userData.id)
                .find()
                .then((results) => {
                    $w("#html1").postMessage({
                        action: "loadResult",
                        items: results.items
                    });
                })
                .catch((err) => {
                    console.error("Load error:", err);
                    $w("#html1").postMessage({ action: "loadResult", items: [] });
                });
        }
    });
});
