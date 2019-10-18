<div class="user-prompt-overlay">
	<div class="user-prompt-wrapper">
		<div class="user-prompt-container">
			<div class="user-prompt-top">
				<?php echo $back_icon; ?>
				<?php echo $close_icon; ?>
			</div>
			<div class="user-prompt-bottom">
				<div class="user-prompt-dynamic">
					<div class="user-prompt-section user-prompt-edit">
						<button class="user-prompt-action delete">Delete</button>
						<button class="user-prompt-action rename">Rename</button>
						<button class="user-prompt-action change-image">Change Image</button>
					</div>
					<div class="user-prompt-section user-prompt-edit-delete">
						<button class="user-prompt-label">Are you sure?</button>
						<button class="user-prompt-action half-button cancel">No</button>
						<button class="user-prompt-action half-button confirm">Yes</button>
					</div>
					<div class="user-prompt-section user-prompt-edit-rename">
						<input class="user-prompt-input rename" type="text" placeholder="Title...">
						<button class="user-prompt-action half-button cancel">Cancel</button>
						<button class="user-prompt-action half-button confirm">Confirm</button>
					</div>
					<div class="user-prompt-section user-prompt-edit-image">
						<input class="user-prompt-input hidden image" type="file">
						<button class="user-prompt-action upload-image">Choose Image</button>
						<div class="user-prompt-change-wrapper">
							<img class="user-prompt-change-image" src="">
							<div class="user-prompt-change-image-overlay">
								<span class="user-prompt-change-text noselect">Square Image Only</span>
							</div>
						</div>
						<button class="user-prompt-action half-button cancel">Cancel</button>
						<button class="user-prompt-action half-button confirm">Confirm</button>
					</div>
					<div class="user-prompt-section user-prompt-add">
						<input class="user-prompt-input hidden image" type="file">
						<button class="user-prompt-action upload-image">Choose Image</button>
						<div class="user-prompt-placeholder-wrapper">
							<img class="user-prompt-placeholder-image" src="">
							<div class="user-prompt-placeholder-image-overlay">
								<span class="user-prompt-placeholder-text noselect">Square Image Only</span>
							</div>
						</div>
						<input class="user-prompt-input title" type="text" placeholder="Title...">
						<button class="user-prompt-action submit">Confirm</button>
					</div>
					<div class="user-prompt-section user-prompt-catalogue">
						<div class="user-prompt-catalogue-image-wrapper">
							<img class="user-prompt-catalogue-image" src="">
						</div>
						<input class="user-prompt-input title" type="text" placeholder="Title..." value="">
						<button class="user-prompt-action add">Add To Collection</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="settings-page-overlay">
	<div class="settings-page-saving-overlay">
		<button class="settings-page-saving-label">Saving...</button>
	</div>
	<div class="settings-page-wrapper">
		<div class="settings-page-container">
			<div class="settings-page-top">
				<?php echo $back_icon; ?>
				<?php echo $close_icon; ?>
			</div>
			<div class="settings-page-bottom">
				<div class="settings-page-left">
					<button class="settings-page-button menu-button appearance">Appearance</button>
					<button class="settings-page-button menu-button options">Options</button>
					<button class="settings-page-button menu-button account">Account</button>
					<button class="settings-page-button menu-button backup">Backup</button>
				</div>
				<div class="settings-page-right">
					<div class="settings-pane appearance-pane">
						<div class="settings-section">
							<button class="settings-page-label">Theme</button>
							<button class="settings-page-button choice-button half-button action-light-theme">Light</button>
							<button class="settings-page-button choice-button half-button action-dark-theme">Dark</button>
						</div>
						<div class="settings-section">
							<button class="settings-page-label">Accent Color</button>
							<button class="settings-page-button choice-button half-button action-accent-green">Green</button>
							<button class="settings-page-button choice-button half-button action-accent-blue">Blue</button>
							<button class="settings-page-button choice-button half-button action-accent-purple">Purple</button>
							<button class="settings-page-button choice-button half-button action-accent-red">Red</button>
							<button class="settings-page-button choice-button half-button action-accent-pink">Pink</button>
							<button class="settings-page-button choice-button half-button action-accent-orange">Orange</button>
						</div>
					</div>
					<div class="settings-pane options-pane">
						<div class="settings-page-text-wrapper">
							<span>Changing these settings might require a refresh.</span>
						</div>
						<div class="settings-section">
							<button class="settings-page-label">Public Collection Page</button>
							<button class="settings-page-button action-button action-view">View Public Page</button>
							<button class="settings-page-button choice-button half-button action-sharing-enable">Enabled</button>
							<button class="settings-page-button choice-button half-button action-sharing-disable">Disabled</button>
						</div>
						<div class="settings-section">
							<button class="settings-page-label">User Preferences</button>
							<button class="settings-page-button action-button action-reset">Reset Settings</button>
						</div>
					</div>
					<div class="settings-pane account-pane">
						<div class="settings-section">
							<button class="settings-page-label">Logout Of Account</button>
							<button class="settings-page-button action-button action-logout">Logout</button>
						</div>
						<div class="settings-page-text-wrapper">
							<span>Changing your username or password requires a refresh. You will also be asked to login again.</span>
						</div>
						<div class="settings-section">
							<button class="settings-page-label">Username</button>
							<input class="settings-page-input password" placeholder="Password..." type="password">
							<input class="settings-page-input username" placeholder="New Username..." type="text">
							<button class="settings-page-button action-button action-username">Change Username</button>
						</div>
						<div class="settings-section">
							<button class="settings-page-label">Password</button>
							<input class="settings-page-input current-password" placeholder="Current Password..." type="password">
							<input class="settings-page-input new-password" placeholder="New Password..." type="password">
							<input class="settings-page-input repeat-password" placeholder="Repeat Password..." type="password">
							<button class="settings-page-button action-button action-password">Change Password</button>
						</div>
					</div>
					<div class="settings-pane backup-pane">
						<div class="settings-section">
							<button class="settings-page-label">Save Backup</button>
							<button class="settings-page-button action-button action-backup">Download Backup</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="help-page-overlay">
	<div class="help-page-wrapper">
		<div class="help-page-container">
			<div class="help-page-top">
				<?php echo $back_icon; ?>
				<?php echo $close_icon; ?>
			</div>
			<div class="help-page-bottom">
				<button class="help-page-label">Adding Items</button>
				<div class="help-page-text-wrapper">
					<span>Adding an item to your collection is easy. You can either add your own items with a custom photo and title, or use the catalogue to add an item to your collection from there. To add a custom item, click on the "+" icon on the "My Collection" page and fill out the required form. <b>Photos you upload will be hosted on Imgur, so do not upload photos you wouldn't otherwise share publically. Deleting an item from your collection or changing its image does delete its photo from Imgur, but on the off chance that there are issues, do not upload photos you wouldn't want others to see</b>. To add an item from the catalogue, click on the menu icon on the top left of the screen and click on "Catalogue". Find the item you'd like to add and click on it. A page will open with a button saying "Add To Collection". Click that button to add the item to your collection.</span>
				</div>
				<button class="help-page-label">Editing Items</button>
				<div class="help-page-text-wrapper">
					<span>To edit an item, you must first have it in your collection, and then click on it to reveal a menu that allows you to delete, rename or change its image. Deleting it will remove the item from your collection, but keep in mind, photos you upload for custom items will stay on Imgur (an image hosting site). The photos aren't publically listed, but anyone with the links can access them, so make sure you don't upload photos you wouldn't otherwise publically share.</span>
				</div>
				<button class="help-page-label">Your Account</button>
				<div class="help-page-text-wrapper">
					<span>An account is required to access this website and its content. Your images and collection aren't publically accessible unless you make your page public. If you make the page public, other users will be able to view your collection but won't be able to modify it. You can change your username and password from the "Settings" page by clicking on the menu icon in the top left of the screen and clicking on "Settings". Changing your username or password requires you to login again.</span>
				</div>
				<button class="help-page-label">Backups</button>
				<div class="help-page-text-wrapper">
					<span>This website might get deleted or modified at any time, so the responsibility of keeping your collection safe is almost entirely on you. You can download a backup of your collection by clicking on the menu icon in the top left of the screen, and clicking on "Settings". There will be a settings tab called "Backup" that will allow you to download your collection. Click on the "Download Backup" button which will take you to a page with all your collection items. Wait for the page to finish loading, then, using a browser on a desktop/laptop, right click on the page and click on "Save As..." and save the page anywhere on your computer. You may now open the downloaded ".html" or ".htm" file to view your collection. Keep in mind, that ".html" or ".htm" file comes with a folder next to it that contains the images and layout of your collection, so always keep that file and folder together.</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="notification-area"></div>