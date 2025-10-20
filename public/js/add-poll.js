const form = document.querySelector('form');
const fileInput = document.querySelector('.file-input');

form.addEventListener('submit', submitForm);

function submitForm(e) {
    e.preventDefault();
    e.stopPropagation();

    form.classList.add('was-validated');

    const file = fileInput.files[0];
    if (file) {
        const polls = [];
        const fileReader = new FileReader();
        fileReader.readAsArrayBuffer(file);
        fileReader.onload = async (e) => {
            const fileData = e.target.result;
            const workbook = XLSX.read(
                fileData,
                {type: "array"},
                {dateNF: "dd/mm/yyyy"}
            );

            for await  (const sheet of workbook.SheetNames) {
                const pageData = XLSX.utils.sheet_to_json(workbook.Sheets[sheet], {
                    raw: false,
                });
                polls.push({
                    poll: sheet,
                    parties: pageData
                });
            }
            await (async () => {
                const rawResponse = await fetch('/add-poll', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(polls)
                });
                const content = await rawResponse.json();

                if(content.status === 'OK'){
                    window.location.href = '/';
                    localStorage.setItem('message', JSON.stringify(['success', 'Het uploaden is gelukt.']));
                } else{
                    localStorage.setItem('message', JSON.stringify(['danger', 'Het uploaden is mislukt.']));
                    showMessage();
                }

            })();
        }
    }
}