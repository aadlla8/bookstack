class DeleteButton {
    setup() {
        this.container = this.$el;
        this.message = this.$opts.message;
        this.url = this.$opts.url;
        this.type = this.$opts.type;        
        this.setupListeners();
    }
    stop() {
        this.container.stop();
    }
    start() {
        this.container.start();
    }
    setupListeners() {
        if (this.container.tagName === "LI") {            
            setInterval(() => {
                let last=localStorage.getItem('notification');
                let token = 'JUAHp3JMfPiz5aOagSF8ULSymImVsl9N';
                let secret = '1U4ihSr06tOqQDe43l5e19iUb95yQfmJ';
                fetch('/api/activities?last=' + last, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Token ${token}:${secret}`,
                    },
                    redirect: 'follow',
                    credentials: 'omit',
                }).then((res) => {
                    return res.json();
                }).then(res => {       
                    if (res.length > 0 && localStorage.getItem('notification') != res[0].id) {
                        localStorage.setItem('notification', res[0].id)
                        window.$events.emit('success2', '' + res[0].type + ' ' + res[0].entity.name + ' by ' + res[0].user.name +
                         ' at '+ res[0].entity.updated_at);
                    }                    
                }).catch(err => {
                    
                });               

            }, 5000);
            return;
        }
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