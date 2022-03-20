function updateHeader(){
    //get placeholder or value for title
    let jumbotronTitleVal = document.getElementById("jumbotronTitle").value;
    let jumbotronTitle = (jumbotronTitleVal === "") ? document.getElementById("jumbotronTitle").placeholder : jumbotronTitleVal;
    let jumbotronDescription = document.getElementById("jumbotronDescription").value;
    let jumbotronButton = document.getElementById("jumbotronButton").value;
    
    let jumbotron = [jumbotronTitle, jumbotronDescription, jumbotronButton];
    console.log(jumbotron);
    var xhr = new XMLHttpRequest();
    // Track the state changes of the request.
        xhr.onreadystatechange = function () {
            const DONE = 4; // readyState 4 means the request is done.
            const OK = 200; // status 200 is a successful return.
            if (xhr.readyState === DONE) {
                if (xhr.status === OK) {
                    //console.log(xhr.responseText); // 'This is the output.'
                    console.log(xhr.readyState);
                } else {
                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                }
            }
        };
    xhr.open("GET", "http://class/onlineStore/edit/editorWrite.php?q="+jumbotron, true);
    xhr.send();
}
