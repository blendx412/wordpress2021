function klIdentifyBrowser(klUser){if(klUser.current_user_email){var _learnq=window._learnq||[];_learnq.push(["identify",{"$email":klUser.current_user_email}]);}else{if(klUser.commenter_email){_learnq.push(["identify",{"$email":klUser.commenter_email}]);}}}
window.addEventListener("load",function(){klIdentifyBrowser(klUser);});
;