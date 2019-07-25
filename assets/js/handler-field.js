var detectChangeField = (e) => {
    console.log(e);
}

document.onreadystatechange = () => {
    if(document.readyState == 'complete') {
        //can't touch name 'cancha' on elementor form
        var field = document.querySelector('[data-element]');

        var keyAttrData = field.getAttribute('data-element');

        if(keyAttrData == 'jl-elementor-laravel-api-field') {
            
            field.addEventListener('change',detectChangeField);

        }
    
    }
}