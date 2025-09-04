import React, { useMemo, useCallback } from "react";
import { useParams, useNavigate } from "react-router-dom";
import ProgressBar from "../components/ProgressBar";
import Badge from "../components/Badge";
import AchievementItem from "../components/AchievementItem";
import UserStatsSkeleton from "../components/UserStatsSkeleton";
import UserStatsError from "../components/UserStatsError";
import { useUserAchievements } from "../hooks/useAchievements";
import { useUser } from "../hooks/useUsers";
import {
  Setting,
  ShoppingBag,
  ArrowLeft,
  Star,
  Gift,
  Crown,
} from "iconsax-reactjs";

const achievementIcons = {
  first_purchase: ShoppingBag,
  loyal_customer: Star,
  big_spender: Gift,
  premium_shopper: Crown,
} as const;

const UserStats: React.FC = () => {
  const { userId } = useParams<{ userId: string }>();
  const navigate = useNavigate();

  const { isLoading: userLoading } = useUser(userId || "");
  const {
    data: achievementsData,
    isLoading: achievementsLoading,
    isError,
    error,
    refetch,
  } = useUserAchievements(userId || "");

  const handleBackToDashboard = useCallback(() => {
    navigate("/");
  }, [navigate]);

  const handleRetry = useCallback(() => {
    refetch();
  }, [refetch]);

  const isLoading = useMemo(
    () => userLoading || achievementsLoading,
    [userLoading, achievementsLoading]
  );

  const achievements = useMemo(
    () => achievementsData?.data?.next_available_achievements || [],
    [achievementsData?.data?.next_available_achievements]
  );

  const unlockedAchievements = useMemo(
    () => achievementsData?.data?.unlocked_achievements || [],
    [achievementsData?.data?.unlocked_achievements]
  );

  const currentBadge = useMemo(
    () => achievementsData?.data?.current_badge || "bronze",
    [achievementsData?.data?.current_badge]
  );

  const nextBadge = useMemo(
    () => achievementsData?.data?.next_badge || null,
    [achievementsData?.data?.next_badge]
  );

  const remainingToUnlockNextBadge = useMemo(
    () => achievementsData?.data?.remaining_to_unlock_next_badge || 0,
    [achievementsData?.data?.remaining_to_unlock_next_badge]
  );

  const badgeProgress = useMemo(() => {
    const remaining = remainingToUnlockNextBadge;
    
    if (remaining === 0) return 100; 
    
    const estimatedTotal = remaining < 10 ? remaining + 10 : remaining * 2;
    const current = estimatedTotal - remaining;
    
    return Math.max(0, current);
  }, [remainingToUnlockNextBadge]);

  if (isLoading) {
    return <UserStatsSkeleton />;
  }

  if (isError) {
    return (
      <UserStatsError
        error={error?.message || null}
        onRetry={handleRetry}
        onBackToDashboard={handleBackToDashboard}
      />
    );
  }

  return (
    <div className="mx-auto">
      {/* Back button and header */}
      <div className="mb-6 sm:mb-8">
        <button
          onClick={handleBackToDashboard}
          className="flex items-center gap-2 text-primary-green hover:text-primary-green/80 mb-4 transition-colors"
        >
          <ArrowLeft size="20" />
          <span className="text-sm font-medium">Back to Users</span>
        </button>

        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
          Badge & Achievement Overview
        </h1>
        <p className="text-gray-600">
          View user's achievements, badges, and progress
        </p>
      </div>

      {/* achievements & badge grid */}
      <div className="grid sm:grid-cols-3 gap-6 sm:gap-8 md:gap-10 lg:gap-12 xl:gap-14 items-start">
        <div className="md:col-span-2">
          <div className="bg-primary-green py-6 px-4 rounded-lg relative">
            <div className="pointer-events-none absolute inset-0 z-10 h-full w-full bg-[url('/public/content.svg')] bg-contain bg-right bg-no-repeat"></div>
            <div className="flex flex-col justify-between md:items-center md:flex-row">
              <span className="text-white text-2xl font-bold">
                {nextBadge
                  ? `Next: ${
                      nextBadge.charAt(0).toUpperCase() + nextBadge.slice(1)
                    } Badge`
                  : "Badge Progress"}
              </span>
              <span className="text-custom-border-color text-base font-medium">
                {remainingToUnlockNextBadge} purchases left to unlock next badge
              </span>
            </div>
            {/* progress bar */}
            <ProgressBar
              value={badgeProgress}
              max={remainingToUnlockNextBadge < 10 ? remainingToUnlockNextBadge + 10 : remainingToUnlockNextBadge * 2}
              className="mt-4"
              color="bg-white"
              backgroundColor="bg-gray-50/40"
              animated={true}
            />
          </div>

          {/* next available achievements */}
          <div className="text-black border custom-border-color py-6 px-4 w-full rounded-lg relative mt-10">
            <h1 className="text-2xl font-bold mb-6">Achievements</h1>
            <div className="flex flex-col gap-6">
              {achievements.length > 0 ? (
                achievements.map((achievement) => {
                  const IconComponent =
                    achievementIcons[
                      achievement.key as keyof typeof achievementIcons
                    ] || ShoppingBag;

                  return (
                    <AchievementItem
                      key={achievement.id}
                      icon={IconComponent}
                      title={achievement.name}
                      description={achievement.description}
                      value={parseFloat(achievement.current_progress as string)}
                      max={parseFloat(achievement.current_progress as string) + achievement.remaining}
                    />
                  );
                })
              ) : (
                <div className="text-center py-8">
                  <p className="text-gray-500">No achievements available</p>
                </div>
              )}
            </div>
          </div>

          {/* Unlocked Achievements Section */}
          {unlockedAchievements.length > 0 && (
            <div className="text-black border custom-border-color py-6 px-4 w-full rounded-lg relative mt-10">
              <h1 className="text-2xl font-bold mb-6">Unlocked Achievements</h1>
              <div className="flex flex-col gap-6">
                {unlockedAchievements.map((achievement) => {
                  const IconComponent =
                    achievementIcons[
                      achievement.key as keyof typeof achievementIcons
                    ] || ShoppingBag;

                  return (
                    <AchievementItem
                      key={achievement.id}
                      icon={IconComponent}
                      title={achievement.name}
                      description={achievement.description}
                      value={achievement.current_progress as number}
                      max={achievement.current_progress as number}
                    />
                  );
                })}
              </div>
            </div>
          )}
        </div>
        <div className="md:col-span-1">
          {/* Badges Section */}
          <div className="border custom-border-color py-6 px-4 w-full rounded-lg relative">
            <h1 className="text-2xl font-bold mb-6 text-black">Badges</h1>
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-6 sm:gap-3 md:gap-4 lg:gap-8 xl:gap-10 w-full">
              <Badge
                icon={Setting}
                label="Bronze"
                isActive={currentBadge === "bronze"}
                isNext={nextBadge === "bronze"}
              />
              <Badge
                icon={Setting}
                label="Silver"
                isActive={currentBadge === "silver"}
                isNext={nextBadge === "silver"}
              />
              <Badge
                icon={Setting}
                label="Gold"
                isActive={currentBadge === "gold"}
                isNext={nextBadge === "gold"}
              />
              <Badge
                icon={Setting}
                label="Diamond"
                isActive={currentBadge === "diamond"}
                isNext={nextBadge === "diamond"}
              />
               <Badge
                icon={Setting}
                label="Ruby"
                isActive={currentBadge === "ruby"}
                isNext={nextBadge === "ruby"}
              />
              <Badge
                icon={Setting}
                label="Platinum"
                isActive={currentBadge === "platinum"}
                isNext={nextBadge === "platinum"}
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default UserStats;
