import './bootstrap';
import './sidebar';
import './dropdown';
import './modal';
import './validation';
import "./pages/organizations/index";
import "./session-flash";
import { createIcons, icons } from 'lucide';

import { refreshIcons } from "./utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
    refreshIcons();
});