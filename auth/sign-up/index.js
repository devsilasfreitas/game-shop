function setImage () {
    const link = document.getElementById("photoUrl").value;
    if (link.length < 2) {
        document.getElementById("avatar").src = "https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg";
        return;
    }
    fetch(link).then((response) => {
        if(response.status == "200") {
            document.getElementById("avatar").src = link;
        }
    });
}