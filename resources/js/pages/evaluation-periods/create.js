import DatePicker from "../../components/date-picker/date-picker";


// ==========================================
// Start Date
// ==========================================

const startDate = DatePicker("#start_date");


// ==========================================
// End Date
// ==========================================

const endDate = DatePicker("#end_date");


// ==========================================
// Start Date Changed
// ==========================================

if (startDate && endDate) {

    startDate.config.onChange.push(
        function (selectedDates) {

            if (!selectedDates.length) {
                return;
            }

            endDate.set("minDate", selectedDates[0]);

        }
    );

}