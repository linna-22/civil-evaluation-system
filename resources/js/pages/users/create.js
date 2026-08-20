import { get } from "../../utils/api";
import TomSelect from "../../plugins/tom-select";

const organization = document.getElementById("organization");
const department = document.getElementById("department");
const office = document.getElementById("office");

if (organization && department && office) {

    // ==========================================
    // Organization
    // ==========================================

    const organizationSelect = new TomSelect(organization, {
        create: false,
        allowEmptyOption: true,
        placeholder: "ជ្រើសរើសស្ថាប័ន",
    });


    // ==========================================
    // Department
    // ==========================================

    const departmentSelect = new TomSelect(department, {
        create: false,
        allowEmptyOption: true,
        placeholder: "ជ្រើសរើសនាយកដ្ឋាន",
    });


    // ==========================================
    // Office
    // ==========================================

    const officeSelect = new TomSelect(office, {
        create: false,
        allowEmptyOption: true,
        placeholder: "ជ្រើសរើសការិយាល័យ",
    });


    // ==========================================
    // Load Departments
    // ==========================================

    async function loadDepartments(
        organizationId,
        selectedDepartment = null
    ) {

        departmentSelect.clear();
        departmentSelect.clearOptions();

        // Reset Office whenever department changes
        officeSelect.clear();
        officeSelect.clearOptions();
        officeSelect.disable();

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

                await loadOffices(
                    selectedDepartment,
                    office.dataset.selected
                );
            }

            departmentSelect.refreshOptions(false);

        } catch (error) {

            console.error(error);

        }
    }


    // ==========================================
    // Load Offices
    // ==========================================

    async function loadOffices(
        departmentId,
        selectedOffice = null
    ) {

        officeSelect.clear();
        officeSelect.clearOptions();

        if (!departmentId) {
            officeSelect.disable();
            return;
        }

        try {

            const offices = await get(
                `/offices/by-department/${departmentId}`
            );

            offices.forEach(item => {

                officeSelect.addOption({
                    value: item.office_id,
                    text: item.office_name_kh,
                });

            });

            officeSelect.enable();

            if (selectedOffice) {
                officeSelect.setValue(selectedOffice);
            }

            officeSelect.refreshOptions(false);

        } catch (error) {

            console.error(error);

        }
    }


    // ==========================================
    // Organization Change
    // ==========================================

    organization.addEventListener("change", function () {

        loadDepartments(this.value);

    });


    // ==========================================
    // Department Change
    // ==========================================

    department.addEventListener("change", function () {

        loadOffices(this.value);

    });


    // ==========================================
    // Edit Mode
    // ==========================================

    if (organization.value) {

        loadDepartments(
            organization.value,
            department.dataset.selected
        );

    } else {

        departmentSelect.disable();
        officeSelect.disable();

    }
}