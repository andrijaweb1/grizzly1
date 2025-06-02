<script>
    //kod ne radi, POPRAVITI!
class DateFormatter {
    constructor(dateInputId, outputElementId) {
        this.dateInput = document.getElementById(dateInputId);
        this.outputElement = document.getElementById(outputElementId);

        if (this.dateInput && this.outputElement) {
            this.dateInput.addEventListener('change', () => this.formatDate());
        } else {
            console.error("Elementi nisu pronađeni!");
        }
    }

    formatDate() {
        const dateInputValue = this.dateInput.value; // Vrednost u formatu YYYY-MM-DD
        const date = new Date(dateInputValue);

        // Formatiraj datum u "dan, mesec, godina"
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'long' }); // Pun naziv meseca
        const year = date.getFullYear();

        const formattedDate = `${day}. ${month} ${year}.`;
        this.outputElement.textContent = `Izabrani datum: ${formattedDate}`;
    }
}
</script>