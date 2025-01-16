$(document).ready(function() {
    const $dataTable = $('#dataTable');
    const $tableBody = $dataTable.find('tbody'); 
    const $errorDisplay = $('#errorDisplay');

    const loadCustomerData = async () => {
        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/users/get.php');
            

            let customers = response.data.users;
            // console.log(customers)
            
            let tableRows = '';
            $.each(customers, (index, customer) => {
                tableRows += `<tr>
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.username}</td>
                                <td>${customer.email}</td>
                                <td>${customer.phone}</td>
                                <td>${customer.address}</td>
                                <td>${customer.created_at}</td>
                              </tr>`;
            });
            
            $tableBody.html(tableRows); 
        } catch (error) {
            console.error('Error loading customer data:', error);
            $errorDisplay.html('Failed to load customer data. Please try again.'); 
        }
    };
    loadCustomerData();
});
