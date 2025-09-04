import { apiClient } from '../config'
import type { UserAchievementsResponse } from '../types'

export const achievementService = {
  getUserAchievements: async (userId: string): Promise<UserAchievementsResponse> => {
    const response = await apiClient.get(`/users/${userId}/achievements`)
    return response.data
  }
}