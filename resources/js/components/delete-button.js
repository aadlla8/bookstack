class DeleteButton {
    setup() {
        this.container = this.$el;
        this.message = this.$opts.message;
        this.url = this.$opts.url;
        this.type = this.$opts.type;
        this.setupListeners();
    }
    setupListeners() {
        if (this.container.tagName === 'A' && this.type=='export') {
            this.container.addEventListener('click', this.export.bind(this));           
            return;
        }
        if (this.container.tagName === 'A') {
            this.container.addEventListener('click', this.click.bind(this));
            return;
        }        
    }
    click() {
        event.preventDefault();
        if (confirm(this.message)) {
            location.href = this.url;
        }
    }
    export() {
        let html = document.getElementById('student_results').outerHTML;
        
        var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        location.href = url;
    }
}
export default DeleteButton;