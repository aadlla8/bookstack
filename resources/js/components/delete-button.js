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
                let lastActivityId=localStorage.getItem('notification');
                let token = 'bRkuQlKaKnvmXxcw6e5NFyiGTlDtxgxp';
                let secret = '6QxpsCdERVsqUyFjpsQH99NTuVJ9ZV2h';
                fetch('/api/activities?last=' + lastActivityId, {
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
                        if (res[0].entity.name != '') {
                            window.$events.emit('success2', '' + res[0].type + ' ' + res[0].entity.name + ' by ' + res[0].user.name +
                            ' at '+ res[0].entity.updated_at);
                        }                       
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