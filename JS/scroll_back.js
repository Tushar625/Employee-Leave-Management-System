const localStorageKey = 'scrollPosition'; // Clear and descriptive key

// Function to save scroll position to localStorage
function saveScrollPosition() {
	const scrollTop = window.scrollY; // Get current scroll position
	localStorage.setItem(localStorageKey, scrollTop);
}

// Function to restore scroll position from localStorage
function restoreScrollPosition() {
	const storedPosition = localStorage.getItem(localStorageKey);
	if (storedPosition !== null) {
		window.scrollTo({left: 0, top: parseInt(storedPosition), behavior: "smooth"}); // Ensure integer conversion
	}
}

// Add event listeners for saving and restoring scroll position
window.addEventListener('click', saveScrollPosition);
window.addEventListener('load', restoreScrollPosition);