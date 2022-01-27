
class TreeView {
    setup() {
        this.container = this.$el;
        
       
        this.setupListeners();
    }
    setupListeners(){
        this.container.addEventListener('click', this.export.bind(this));     
    }

    export() {
        alert('hello');
    }
    
}

export default TreeView