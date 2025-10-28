class Party {
    constructor(name, seats, enabled = true, firstChamberSeats) {
        this.name = name;
        this.seats = seats;
        this.enabled = enabled;
        this.firstChamberSeats = firstChamberSeats;
    }

    createCheckElement() {
        const checkWrapper = document.createElement('div');
        checkWrapper.classList.add('form-check');
        const input = document.createElement('input');
        input.setAttribute('type', 'checkbox');
        input.classList.add('form-check-input', 'party');
        input.setAttribute('id', this.name);
        if (this.enabled) {
            input.setAttribute('checked', '');
        }
        const label = document.createElement('label');
        label.setAttribute('for', this.name);
        label.classList.add('form-check-label');
        label.textContent = this.name;
        checkWrapper.appendChild(input);
        checkWrapper.appendChild(label);
        return checkWrapper;
    }

    createResultHTML() {
        const tr = document.createElement("tr");
        const th = document.createElement('th');
        th.setAttribute('scope', 'row');
        th.textContent = this.name;
        tr.appendChild(th);
        const tdChairs = document.createElement('td');
        tdChairs.textContent = this.seats;
        tr.appendChild(tdChairs);
        const tdPercent = document.createElement('td');
        tdPercent.textContent = (this.firstChamberSeats / 75 * 100).toFixed(2).replace(".", ",") + "%";
        tr.appendChild(tdPercent);
        return tr;
    }

}