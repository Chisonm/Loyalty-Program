import { useQuery } from '@tanstack/react-query'
import { achievementService } from '../api/services/achievements'
import type { UserAchievementsResponse } from '../types'

// Query keys
export const achievementKeys = {
  all: ['achievements'] as const,
  user: (userId: string) => [...achievementKeys.all, 'user', userId] as const,
}

// Custom hook for user achievements
export const useUserAchievements = (userId: string) => {
  return useQuery<UserAchievementsResponse>({
    queryKey: achievementKeys.user(userId),
    queryFn: () => achievementService.getUserAchievements(userId),
    enabled: !!userId, // Only run query if userId exists
    staleTime: 5 * 60 * 1000, // Data is fresh for 5 minutes
    gcTime: 10 * 60 * 1000, // Cache for 10 minutes
  })
}