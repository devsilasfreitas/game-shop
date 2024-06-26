function setImage () {
    const link = document.getElementById("logoUrl").value;
    if (link.length < 2) {
        document.getElementById("logo").src = "https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg";
        return;
    }
    fetch(link).then((response) => {
        if(response.status == "200") {
            document.getElementById("logo").src = link;
        }
    });
}

function setBanner () {
    const link = document.getElementById("bannerUrl").value;
    if (link.length < 2) {
        document.getElementById("banner").src = "https://i.ytimg.com/vi/3gDcG9qDfME/hqdefault.jpg";
        return;
    }
    fetch(link).then((response) => {
        if(response.status == "200") {
            document.getElementById("banner").src = link;
        }
    });
}