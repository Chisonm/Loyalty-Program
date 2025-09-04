import React from 'react'
import type { HeaderProps } from '../types'
import { Element2 } from 'iconsax-reactjs'

const Header: React.FC<HeaderProps> = ({
  onSidebarToggle,
  title = 'Welcome back',
  user,
  actions,
  className = ''
}) => {
  const renderUserAvatar = () => {
    if (user?.avatar) {
      return (
        <img
          src={user.avatar}
          alt={user.name || 'User'}
          className="w-8 h-8 rounded-full object-cover"
        />
      )
    }

    return (
      <div className="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
        <span className="text-sm font-medium text-gray-600">
          {user?.initials || user?.name?.charAt(0).toUpperCase() || 'U'}
        </span>
      </div>
    )
  }

  return (
    <header className={`bg-white border-b custom-border-color flex-shrink-0 ${className}`}>
      <div className="flex items-center justify-between h-16 px-4 sm:px-6">
        <div className="flex items-center">
          <button
            onClick={onSidebarToggle}
            className="p-2 rounded-md lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <Element2 className="size-4 text-black" />
          </button>
          <h2 className="ml-4 text-lg font-semibold text-gray-800 lg:ml-0">{title}</h2>
        </div>
        
        <div className="flex items-center space-x-2 sm:space-x-4">
          {actions}
          {renderUserAvatar()}
        </div>
      </div>
    </header>
  )
}

export default Header