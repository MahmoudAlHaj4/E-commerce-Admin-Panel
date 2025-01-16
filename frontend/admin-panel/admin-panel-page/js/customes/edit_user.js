$(document).ready(function() {
    const $customerSelect = $('#customerSelect'); 
    const $errorDisplay = $('#errorDisplay'); 

    const loadCustomerDropdown = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/users/get.php');

            let customers = response.data.users; 

            $customerSelect.append('<option value="">Select a customer</option>');

            
            $.each(customers, (index, customer) => {
                $customerSelect.append(new Option(customer.name, customer.id));
            });
        } catch (error) {
            console.error('Error fetching customers for dropdown:', error);
            $errorDisplay.html('Failed to load customer options. Please try again.');
        }
    };

 
    $('#editCustomerModal').on('show.bs.modal', loadCustomerDropdown);

    $customerSelect.on('change', async function() {
        const userId = $(this).val();

        if (userId) {
            try {
                const response = await axios.get(`http://localhost:3000/e-commerce-inventory-management/backend/users/get.php?customer_id=${userId}`);
                const user = response.data.user;

                $('#editCustomerId').val(user.id);
                $('#editCustomerEmail').val(user.email);
                $('#editCustomerPhone').val(user.phone);
                $('#editCustomerAddress').val(user.address);
            } catch (error) {
                console.error('Error fetching customer details:', error);
            }
        }
    });

    $('#updateCustomerBtn').on('click', async function() {
        const userId = $('#editCustomerId').val();
    
        const email = $('#editCustomerEmail').val();
        const phone = $('#editCustomerPhone').val();
        const address = $('#editCustomerAddress').val();
    
        try {
            const response = await axios({
                method: 'post',
                url: 'http://localhost:3000/e-commerce-inventory-management/backend/users/update.php',
                // headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: new URLSearchParams({
                    user_id: userId,
                    email: email,
                    phone: phone,
                    address: address
                })
            });
    
            if (response.data.status === 'success') {
                console.log('User updated successf')
                console.log(response.data)
                $('#editCustomerModal').modal('hide');
                location.reload()
            } else {
                console.log(response.data);
            }
        } catch (error) {
            console.error('Error updating user:', error);
        }
    });
    

});

