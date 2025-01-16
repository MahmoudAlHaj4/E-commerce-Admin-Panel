$(document).ready(function() {
    const $CustomerForm = $('.addCustomerForm'); 
    const $usernameInput = $('#customerUsername'); 
    const $emailInput = $('#customerEmail');
    const $passwordInput = $('#customerpassword'); 
    const $nameInput = $('#customerName')
    const $phoneInput = $('#customerPhone')
    const $addressInput = $('#customerAddress')
    const $modal = $('#addCustomerModal');



    const addCustomer = async (username, email, password, name, phone, address) => {
        const data = new FormData();
        data.append('username', username);
        data.append('email', email);
        data.append('password', password);
        data.append('name', name);
        data.append('phone', phone);
        data.append('address', address);

        try {

            const response = await axios.post('http://localhost:3000/e-commerce-inventory-management/backend/users/signup.php', data);
            
            console.log('API response:', response.data); 
            if (response.data && response.data.status === 'success') {
                console.log('add successful:', response.data);
                $modal.modal('hide');
                location.reload();

            } else {
                console.log(response.data.message || 'adding Customer failed. Please try again.');
            }
        } catch (error) {
            console.error('Error during adding:', error);

        }
    };

    $CustomerForm.on('submit', (event) => {
        event.preventDefault(); 
        const username = $usernameInput.val();
        const email = $emailInput.val();
        const password = $passwordInput.val();
        const name = $nameInput.val();
        const phone = $phoneInput.val();
        const address = $addressInput.val();


        addCustomer(username, email, password, name, phone, address);
    });
});
