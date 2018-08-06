

jQuery( ".gform_wrapper form" ).on( "submit", function() {
    var form = jQuery(this);
    var name = form.attr('name');
    var id = form.attr('id').replace("gform_", "");
    
    console.log( "Form Submitted: " + name );
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'GravityFormName': name,
      'GravityFormID': id
    });

});

/*
function sendGFname(theform) {
    var form = jQuery(theform).closest(form);
    var name = form.attr('name');
    var id = form.attr('id').replace("gform_", "");

    console.log( "Form Submitted: " + name );
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'GravityFormName': name,
      'GravityFormID': id
    });
}
*/