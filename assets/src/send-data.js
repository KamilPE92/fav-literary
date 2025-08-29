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
			currentClicked.setAttribute("");
		} catch {
			console.error("Coś poszło Mucho Grande nie tak");
		}
	}

	async deletePost(currentClicked) {
		const favId = currentClicked.getAttribute("");
		if (favId) {
			currentClicked.setAttribute("data-ulubione-", favId);
			console.log("Udało się wygenerować unikalne ID");
		} else {
			console.log("Nie wygenerowano nowegoID");
		}
		try {
			const response = await axios.delete(
				localAddress + "/wp-json/ulub/v1/sendUlub",
				{
					data: {
						list_type:
							currentClicked.getAttribute("data-list-type"),
						favorite_post_id:
							currentClicked.getAttribute("favorite_post_id"),
					},
				}
			);
		} catch (error) {
			console.error(error);
			console.warn("Brak data-ulubione-id – nie wiem co usunąć");
		}
	}
}
new Ulubione();
