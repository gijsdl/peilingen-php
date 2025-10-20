const messageDiv = document.querySelector('.message');
const messageColorDiv = document.querySelector('.alert');
const messageP = document.querySelector('.message span');

showMessage();

function showMessage(){
    const messageJSON = localStorage.getItem('message');
    if (messageJSON){
        const message = JSON.parse(messageJSON);
        messageDiv.classList.remove('hidden');
        messageColorDiv.classList.add(`alert-${message[0]}`);
        messageP.textContent = message[1];
        localStorage.removeItem('message');
    }
}