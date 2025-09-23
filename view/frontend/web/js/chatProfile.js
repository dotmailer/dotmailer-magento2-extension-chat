/**
 * Configuration for chat profile.
 */
const chatProfileConfig = JSON.parse(document.getElementById('dotdigital-chat-profile-config').textContent);
const {apiSpaceId, isEnabled, apiHost, profileEndpoint, cookieName} = chatProfileConfig;

if(isEnabled) {
    window.addEventListener ('message', (event) => {
        const {type, show} = event.data;

        if (type !== 'SetWidgetState') return;

        if (show === 'hidden') {
            sessionStorage.removeItem (cookieName);
        } else if (!sessionStorage.getItem (cookieName)) {
            window.COMAPI_WIDGET_API.profile.getProfile ()
                .then (profile => {
                    fetch (profileEndpoint, {
                        method: 'POST',
                        body: "profileId=" + profile.id,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    })
                        .then (() => {
                            sessionStorage.setItem (cookieName, profile.id);
                            document.cookie = `${cookieName}=${profile.id}`;
                        })
                        .catch (console.error);
                });
        }
    });
}
