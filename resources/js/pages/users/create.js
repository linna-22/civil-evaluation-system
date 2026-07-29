import { get } from "../../utils/api";
import TomSelect from "../../plugins/tom-select";

const organization = document.getElementById("organization");
const department = document.getElementById("department");

if (organization && department) {

    const organizationSelect = new TomSelect(organization, {
        create: false,
        allowEmptyOption: true,
        placeholder: "ជ្រើសរើសស្ថាប័ន",
    });

    const departmentSelect = new TomSelect(department, {
        create: false,
        allowEmptyOption: true,
        placeholder: "ជ្រើសរើសនាយកដ្ឋាន",
    });

    async function loadDepartments(organizationId, selectedDepartment = null) {

        departmentSelect.clear();
        departmentSelect.clearOptions();

        if (!organizationId) {
            departmentSelect.disable();
            return;
        }

        try {

            const departments = await get(
                `/departments/by-organization/${organizationId}`
            );

            departments.forEach(item => {
                departmentSelect.addOption({
                    value: item.department_id,
                    text: item.department_name_kh,
                });
            });

            departmentSelect.enable();

            if (selectedDepartment) {
                departmentSelect.setValue(selectedDepartment);
            }

            departmentSelect.refreshOptions(false);

        } catch (error) {
            console.error(error);
        }
    }

    organization.addEventListener("change", function () {
        loadDepartments(this.value);
    });

    // ===== Edit Mode =====
    if (organization.value) {
        loadDepartments(
            organization.value,
            department.dataset.selected
        );
    } else {
        departmentSelect.disable();
    }
}