var input = document.querySelectorAll('.ui-input input');

for(var i=0;i<input.length;i++){
    input[i].onfocus = function() {
        this.parentNode.className += ' focus';
    }
    input[i].onblur = function() {
        this.parentNode.className = this.parentNode.className.replace(' focus', '');
    }
}