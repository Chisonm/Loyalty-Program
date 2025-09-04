import { apiClient } from '../config'
import type { UsersResponse } from '../types'

export const userService = {
  getUsers: async (): Promise<UsersResponse> => {
    const response = await apiClient.get('/users')
    return response.data
  },

  getUserById: async (userId: string) => {
    const response = await apiClient.get(`/users/${userId}`)
    return response.data
  },
}