//Perform AJAX call to give form status
$('#contactForm').on('submit', function(e) {
    e.preventDefault();
    var details = $('#contactForm').serialize();
    $.post('/php/contact.php', details, function(data) {
        document.getElementById("contactForm").reset();
        $('#formStatus').html(data);
        var reset = grecaptcha.reset();
    });
});