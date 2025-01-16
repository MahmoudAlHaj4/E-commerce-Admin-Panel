$(document).ready(()=>{
    const $dataTable = $('#dataTable');
    const $tableBody = $dataTable.find('tbody'); 
    const $errorDisplay = $('#errorDisplay');

    const loadCategories = async () => {

        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/category/get_all.php')

            let categories = response.data.categories
            console.log(categories)

            let tableRows = '';
            $.each(categories, (index, category) => {
                tableRows += `<tr>
                                <td>${category.id}</td>
                                <td>${category.name}</td>
                                <td>${category.created_at}</td>
                              </tr>`;
            });
            
            $tableBody.html(tableRows); 
        } catch (error) {
            console.error('Error loading customer data:', error);
            $errorDisplay.html('Failed to load customer data. Please try again.'); 
        }
    }

    loadCategories()
})