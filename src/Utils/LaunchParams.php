<?php

namespace ezavalishin\VKMA\Utils;

class LaunchParams
{
    protected array $accessTokenSettings;
    protected int $appId;
    protected bool $areNotificationsEnabled;
    protected bool $isAppUser;
    protected bool $isFavourite;
    protected string $language;
    protected string $platform;
    protected string $ref;
    protected int $userId;
    protected ?int $groupId = null;
    protected ?string $viewerGroupRole = null;

    public function __construct(array $accessTokenSettings, int $appId, bool $areNotificationsEnabled, bool $isAppUser, bool $isFavourite, string $language, string $platform, string $ref, int $userId, ?int $groupId = null, ?string $viewerGroupRole = null)
    {
        $this->accessTokenSettings = $accessTokenSettings;
        $this->appId = $appId;
        $this->areNotificationsEnabled = $areNotificationsEnabled;
        $this->isAppUser = $isAppUser;
        $this->isFavourite = $isFavourite;
        $this->language = $language;
        $this->platform = $platform;
        $this->ref = $ref;
        $this->userId = $userId;

        $this->groupId = $groupId;
        $this->viewerGroupRole = $viewerGroupRole;
    }

    public static function fromParams(string $params): self
    {
        parse_str(base64_decode($params), $result);

        $accessTokenSettings = explode(',', $result['vk_access_token_settings']);
        $appId = (int) $result['vk_app_id'];
        $areNotificationsEnabled = (bool) $result['vk_are_notifications_enabled'];
        $isAppUser = (bool) $result['vk_is_app_user'];
        $isFavourite = (bool) $result['vk_is_favorite'];
        $lang = $result['vk_language'];
        $platform = $result['vk_platform'];
        $ref = $result['vk_ref'];
        $userId = (int) $result['vk_user_id'];

        $groupId = isset($result['vk_group_id']) ? (int) $result['vk_group_id'] : null;
        $role = isset($result->vk_viewer_group_role) ? (int) $result['vk_viewer_group_role'] : null;

        return new LaunchParams(
            $accessTokenSettings,
            $appId,
            $areNotificationsEnabled,
            $isAppUser,
            $isFavourite,
            $lang,
            $platform,
            $ref,
            $userId,
            $groupId,
            $role
        );
    }

    /**
     * @return array
     */
    public function getAccessTokenSettings(): array
    {
        return $this->accessTokenSettings;
    }

    /**
     * @return int
     */
    public function getAppId(): int
    {
        return $this->appId;
    }

    /**
     * @return bool
     */
    public function isAreNotificationsEnabled(): bool
    {
        return $this->areNotificationsEnabled;
    }

    /**
     * @return bool
     */
    public function isAppUser(): bool
    {
        return $this->isAppUser;
    }

    /**
     * @return bool
     */
    public function isFavourite(): bool
    {
        return $this->isFavourite;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    /**
     * @return string|null
     */
    public function getViewerGroupRole(): ?string
    {
        return $this->viewerGroupRole;
    }

    public function roleIsNone(): bool
    {
        return $this->getViewerGroupRole() === 'none';
    }

    public function roleIsMember(): bool
    {
        return $this->getViewerGroupRole() === 'member';
    }

    public function roleIsModer(): bool
    {
        return $this->getViewerGroupRole() === 'moder';
    }

    public function roleIsEditor(): bool
    {
        return $this->getViewerGroupRole() === 'editor';
    }

    public function roleIsAdmin(): bool
    {
        return $this->getViewerGroupRole() === 'admin';
    }
}
