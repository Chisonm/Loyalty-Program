import React from 'react'
import { Category, ShoppingBag, Tag, Setting2 } from 'iconsax-reactjs'
import Sidebar from '../components/Sidebar'
import Header from '../components/Header'
import type { AppLayoutProps, SidebarNavItem } from '../types'

const AppLayout: React.FC<AppLayoutProps> = ({ 
  children, 
  sidebarTitle = 'Dashboard',
  headerTitle = 'Welcome back',
  navItems,
  user,
  headerActions
}) => {
  const [sidebarOpen, setSidebarOpen] = React.useState<boolean>(false)

  const defaultNavItems: SidebarNavItem[] = [
    { id: "1", icon: Category, label: "Dashboard", href: "/", active: true },
    { id: "2", icon: ShoppingBag, label: "Orders", href: "/Orders" },
    { id: "3", icon: Tag, label: "Products", href: "/Products" },
    { id: "4", icon: Setting2, label: "Settings", href: "/settings" },
  ];

  const toggleSidebar = (): void => {
    setSidebarOpen(!sidebarOpen)
  }

  return (
    <div className="flex h-svh bg-white">
      <Sidebar
        isOpen={sidebarOpen}
        onToggle={toggleSidebar}
        navItems={navItems || defaultNavItems}
        title={sidebarTitle}
      />

      {/* Main content */}
      <div className="flex flex-col flex-1 overflow-hidden">
        <Header
          onSidebarToggle={toggleSidebar}
          title={headerTitle}
          user={user}
          actions={headerActions}
        />

        {/* Page content */}
        <main className="flex-1 overflow-y-auto p-4 sm:p-6">
          {children}
        </main>
      </div>
    </div>
  )
}

export default AppLayout