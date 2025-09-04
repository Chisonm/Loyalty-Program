import { type ComponentType } from 'react'

export interface IconsaxProps {
  size?: string | number
  variant?: 'Linear' | 'Outline' | 'Bold' | 'Broken' | 'TwoTone' | 'Bulk'
  className?: string
  color?: string
}

export interface SidebarNavItem {
  id: string
  label: string
  icon?: ComponentType<IconsaxProps> | null
  href: string
  active?: boolean
}

export interface User {
  name?: string
  avatar?: string
  initials?: string
}

export interface SidebarProps {
  isOpen: boolean
  onToggle: () => void
  navItems: SidebarNavItem[]
  title?: string
  className?: string
}

export interface HeaderProps {
  onSidebarToggle: () => void
  title?: string
  user?: User
  actions?: React.ReactNode
  className?: string
}

export interface AppLayoutProps {
  children: React.ReactNode
  sidebarTitle?: string
  headerTitle?: string
  navItems?: SidebarNavItem[]
  user?: User
  headerActions?: React.ReactNode
}

// API
export interface ApiResponse<T> {
  success: boolean
  message: string
  data: T
}

export interface Achievement {
  id: number
  key: string
  name: string
  description: string
  is_active: boolean
  current_progress: number | string
  progress_percentage: number
  remaining: number
}

export interface UserAchievementsData {
  unlocked_achievements: Achievement[]
  next_available_achievements: Achievement[]
  current_badge: string
  next_badge: string
  remaining_to_unlock_next_badge: number
}

export type UserAchievementsResponse = ApiResponse<UserAchievementsData>

export interface Badge {
  id: string
  name: string
  level: string
  icon?: string
  description?: string
}

export interface ApiUser {
  id: string
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  updated_at: string
}

export interface UsersResponse {
  users: ApiUser[]
}