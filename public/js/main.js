const partiesWrapper = document.querySelector('.parties-wrapper');
const partiesField = document.querySelector('.parties');
const calculateBtn = document.querySelector('.calculate');
const resultField = document.querySelector('.result');
const emptyField = document.querySelector('.empty');

let polls = [];

calculateBtn.addEventListener('click', calculateAndShow);


fetch('/get-polls')
    .then(data =>  data.json())
    .then(jsonData => createPoll(jsonData))
    .catch(()=>{
        localStorage.setItem('message', JSON.stringify(['danger', 'Er is wat mis gegaan met het laden']));
        showMessage();
    });

function createPoll(pollsData) {
    if (pollsData.length < 1){
        emptyField.classList.remove('hidden');
        return;
    }
    Promise.all(pollsData.map(async pollData => {
        const poll = new Poll(pollData.name);
        polls.push(poll);
        await fetch('/get-party/' + pollData.id)
            .then(data => data.json())
            .then(jsonData => poll.createParties(jsonData))
            .catch(()=>{
                localStorage.setItem('message', JSON.stringify(['danger', 'Er is wat mis gegaan met het laden']));
                showMessage();
            });
    }))
        .then(() => {
            calculateAndShow();
            showParties();
        });

}

function showParties() {
    partiesWrapper.classList.remove('hidden');
    partiesField.removeChild(partiesField.firstChild);
    const wrapperRow = document.createElement('div');
    wrapperRow.classList.add('row');
    wrapperRow.classList.add('justify-content-center');
    partiesField.appendChild(wrapperRow);
    wrapperRow.appendChild(polls[0].createPartyHTML());
    wrapperRow.appendChild(showPolls(wrapperRow));
    createEventListeners();
}

function showPolls() {
    const wrapperCol = document.createElement('div');
    wrapperCol.classList.add('col-6');

    const titleRow = document.createElement('div');
    titleRow.classList.add('row');
    const titleCol = document.createElement('div');
    titleCol.classList.add('col');
    titleRow.appendChild(titleCol);
    const title = document.createElement('h5');
    title.textContent = 'Peilingen';
    titleCol.appendChild(title);
    wrapperCol.appendChild(titleRow);

    const dataRow = document.createElement('div');
    dataRow.classList.add('row');
    const dataCol = document.createElement('div');
    dataCol.classList.add('col');
    dataRow.appendChild(dataCol);
    polls.forEach((poll) => {
        dataCol.appendChild(poll.createPollHTML());
    });
    wrapperCol.appendChild(dataRow);

    return wrapperCol;
}

function createEventListeners() {
    const partiesCheckBox = document.querySelectorAll('.party');
    partiesCheckBox.forEach((checkbox) => {
        checkbox.addEventListener('change', changePartyEnabled);
    });
    const pollCheckBox = document.querySelectorAll('.poll');
    pollCheckBox.forEach((checkbox) => {
        checkbox.addEventListener('change', changePollEnabled);
    })
}

function changePartyEnabled(e) {
    const name = e.target.nextSibling.textContent;
    const enabled = e.target.checked;
    polls.forEach((poll) => {
        poll.parties.forEach((party) => {
            if (party.name === name) {
                party.enabled = enabled;
            }
        });
    });
}

function changePollEnabled(e) {
    const name = e.target.nextSibling.textContent;
    const enabled = e.target.checked;
    polls.forEach((poll) => {
        if (poll.name === name) {
            poll.enabled = enabled;
        }
    });
}

function calculateAndShow() {
    resultField.classList.remove('hidden');
    resultField.removeChild(resultField.firstChild);

    const wrapperRow = document.createElement('div');
    wrapperRow.classList.add('row');
    wrapperRow.classList.add('justify-content-center');
    resultField.appendChild(wrapperRow);

    polls.forEach((poll) => {
        if (poll.enabled) {
            const governingParties = [];
            let seats = 0;
            let i = 0;
            while (seats < 75 && i < poll.parties.length) {
                const party = poll.parties[i];
                if (party.enabled) {
                    governingParties.push(party);
                    seats += parseInt(party.seats);
                }
                i++;
            }
            wrapperRow.appendChild(poll.createResultHTML(governingParties, seats));
        }
    });
}