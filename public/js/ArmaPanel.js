function ReqReponse(Rep) {
    if (Rep === "LOGOUT") {
        let cd = window.location.href.split('/');
        window.location.replace("logout.php");
    }
    else if(Rep !== "Success" && Rep !== "InvalideTarget" && !(Rep.startsWith('<tr>'))) {
        console.error(Rep);
        errorRequestToast();
    }
}