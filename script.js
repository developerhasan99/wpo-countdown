const wocWrapper = document.querySelector(".woc-wrapper");
const wocToggleBtn = document.querySelector(".woc-toggle-btn");
const wocToggleBtnIcon = document.querySelector(".woc-toggle-btn svg");

let isWocOpen = true;

wocToggleBtn.onclick = function () {
	if (isWocOpen) {
		wocWrapper.style.bottom = "-" + wocWrapper.clientHeight + "px";
		wocToggleBtnIcon.style.rotate = "180deg";
		isWocOpen = !isWocOpen;
	} else {
		wocWrapper.style.bottom = "0px";
		wocToggleBtnIcon.style.rotate = "0deg";
		isWocOpen = !isWocOpen;
	}
};

//Initialize Flipdown js
const targetTime = new Date(woc_options.countdown_date).getTime() / 1000;
new WocFlipDown(targetTime, "woc-countdown", {
	headings: [
		woc_options.day_label,
		woc_options.hour_label,
		woc_options.minute_label,
		woc_options.second_label,
	],
}).start();
