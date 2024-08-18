import axios from "axios";
const localAddress = window.location.origin;

class Ulubione {
	constructor() {
		this.setupEvents();
	}
	setupEvents() {
		document.addEventListener("DOMContentLoaded", () => {
			document.querySelectorAll(".ulub").forEach((button) => {
				button.addEventListener("click", (e) =>
					this.clickUlubhendler(e)
				);
			});
		});
	}
	async clickUlubhendler(e) {
		e.preventDefault();
		let currebtClicked = e.target.parentElement;
		currebtClicked.classList.toggle("exist");
		console.log(currebtClicked);
		if (currebtClicked.classList.contains("exist")) {
			this.createPost(currebtClicked);
		} else {
			this.deletePost();
		}
	}
	async createPost(currebtClicked) {
		try {
			const response = await axios.post(
				localAddress + "/wp-json/ulub/v1/sendUlub",
				{ listType: currebtClicked.getAttribute("data-list-type") }
			);
			console.log(response);
		} catch {
			console.log("sorry");
		}
	}
	deletePost() {
		alert("Usuwaj");
	}
}

new Ulubione();
console.log(localAddress);
