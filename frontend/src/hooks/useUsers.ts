import { useQuery } from '@tanstack/react-query'
import { userService } from '../api/services/users'
import type { UsersResponse } from '../types'

// Query keys for users
export const userKeys = {
  all: ['users'] as const,
  lists: () => [...userKeys.all, 'list'] as const,
  details: () => [...userKeys.all, 'detail'] as const,
  detail: (id: string) => [...userKeys.details(), id] as const,
}

// Hook to fetch all users
export const useUsers = () => {
  return useQuery<UsersResponse>({
    queryKey: userKeys.lists(),
    queryFn: () => userService.getUsers(),
    staleTime: 5 * 60 * 1000, // Data is fresh for 5 minutes
    gcTime: 10 * 60 * 1000, // Cache for 10 minutes
  })
}

// Hook to fetch a single user
export const useUser = (userId: string) => {
  return useQuery({
    queryKey: userKeys.detail(userId),
    queryFn: () => userService.getUserById(userId),
    enabled: !!userId, // Only run if userId exists
    staleTime: 5 * 60 * 1000,
    gcTime: 10 * 60 * 1000,
  })
}