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
					this.clickUlubheHandller(e)
				);
			});
		});
	}

	async clickUlubheHandller(e) {
		e.preventDefault();

		let currentClicked = e.target.closest("button");
		currentClicked.classList.toggle("exist");

		if (currentClicked.classList.contains("exist")) {
			await this.createPost(currentClicked);
		} else {
			await this.deletePost(currentClicked);
		}
	}

	async createPost(currentClicked) {
		try {
			const response = await axios.post(
				localAddress + "/wp-json/ulub/v1/sendUlub",
				{
					list_type: currentClicked.getAttribute("data-list-type"),
					original_post_id: currentClicked.getAttribute(
						"data-original-post-id"
					),
				}
			);
		} catch {
			console.error();
		}
	}

	async deletePost(currentClicked) {
		const originalID = Number(
			currentClicked.getAttribute("data-original-post-id")
		);
		if (!Number.isFinite(originalID)) {
			console.warn(
				"Nie udało się ustawić akceptowalnej wartości, atrybut data musi być liczbą"
			);
			return;
		}
		try {
			const response = await axios.delete(
				localAddress + "/wp-json/ulub/v1/sendUlub",
				{
					data: {
						original_post_id: originalID,
					},
				}
			);
		} catch {
			console.error();
		}
	}
}
new Ulubione();
