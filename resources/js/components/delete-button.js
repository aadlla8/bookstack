class DeleteButton {
    setup() {
        this.container = this.$el;
        this.message = this.$opts.message;
        this.url = this.$opts.url;
        this.setupListeners();
    }
    setupListeners() {
        
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
}
export default DeleteButton;