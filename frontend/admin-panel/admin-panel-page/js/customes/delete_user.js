$(document).ready(function() {
    const $customerSelect = $('#customerSelect2'); 
    const $errorDisplay = $('#errorDisplay'); 

   
    const loadCustomerDropdown = async () => {
        try {
          
            $customerSelect.empty();
            $customerSelect.append('<option value="">Select a customer</option>');
            
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/users/get.php');
            
            let customers = response.data.users; 
            
            if(customers && customers.length > 0){
               
                $.each(customers, (index, customer) => {
                   
                
                    $customerSelect.append(new Option(customer.name, customer.id));

                });
            } else {
               
                $errorDisplay.html('No customers available.');
            }
        } catch (error) {
            
            console.error(error);
            $errorDisplay.html('Failed to load customer options. Please try again.');
        }
    };

    $('#deleteCustomerModal').on('show.bs.modal', loadCustomerDropdown);


    $('#confirmDeleteButton').on('click', async () => {
        const userId = $customerSelect.val(); 

        try {
            
            const response = await axios({
                method: 'post',
                url: 'http://localhost:3000/e-commerce-inventory-management/backend/users/delete.php',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: new URLSearchParams({
                    customer_id: userId,

                })
            });

            if (response.data.status === 'success') {
              
                $errorDisplay.html('Customer deleted successfully.');
                console.log('user deleted succefully')
                location.reload()
                $('#deleteCustomerModal').modal('hide');
                loadCustomerDropdown();
            } else {
                
                $errorDisplay.html(response.data.message);
            }
        } catch (error) {
            console.error('Error deleting customer:', error);
            $errorDisplay.html('Failed to delete customer. Please try again.');
        }
    });
});


