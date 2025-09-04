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