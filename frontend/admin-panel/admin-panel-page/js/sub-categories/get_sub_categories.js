$(document).ready(()=>{
    const $dataTable = $('#dataTable');
    const $tableBody = $dataTable.find('tbody');

    const LoadSubCategories = async () => {

        try {
            const response = await axios.get('http://localhost:3000/e-commerce-inventory-management/backend/category/sub_category_get.php')

            let subCategories = response.data.subcategories
            console.log(subCategories)

            let tableRows = '';
            $.each(subCategories, (index, subcategory) => {
                tableRows += `<tr>
                                <td>${subcategory.id}</td>
                                <td>${subcategory.name}</td>
                                <td>${subcategory.parent_name}</td>
                                <td>${subcategory.created_at}</td>
                              </tr>`;
            });
            
            $tableBody.html(tableRows); 
        } catch (error) {
            console.log(error)
        }
    }
    LoadSubCategories()
})