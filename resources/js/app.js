import './bootstrap';
import './sidebar';
import './dropdown';
import './modal';
import './validation';
import "./pages/organizations/index";
import "./pages/departments/index";
import "./pages/users/index";
import "./pages/users/create";
import "./session-flash";
import "./plugins/tom-select";
import "./evaluation/performance-table";
import "./evaluation/evaluation-list";

import { refreshIcons } from "./utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
    refreshIcons();
});