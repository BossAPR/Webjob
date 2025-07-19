document.getElementById('edit-button').addEventListener('click', function() {
    document.getElementById('edit-form').classList.add('open');
});

document.getElementById('cancel-button').addEventListener('click', function() {
    document.getElementById('edit-form').classList.remove('open');
});

document.getElementById('save-button').addEventListener('click', function() {
    var firstName = document.getElementById('first-name').value;
    var lastName = document.getElementById('last-name').value;
    var address = document.getElementById('address').value;
    var phone = document.getElementById('phone').value;

    document.getElementById('full-name').innerText = firstName + ' ' + lastName;
    document.getElementById('address').innerText = address;
    document.getElementById('phone').innerText = phone;

    document.getElementById('edit-form').classList.remove('open');
});
