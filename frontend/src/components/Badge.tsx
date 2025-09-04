import React from 'react'
import type { ComponentType } from 'react'
import type { IconsaxProps } from '../types'

interface BadgeProps {
  icon: ComponentType<IconsaxProps>
  label: string
  size?: 'sm' | 'md' | 'lg'
  iconColor?: string
  backgroundColor?: string
  labelColor?: string
  className?: string
}

const Badge: React.FC<BadgeProps> = ({
  icon: Icon,
  label,
  size = 'md',
  iconColor = 'text-green-500',
  backgroundColor = 'bg-[#F8F8F8]',
  labelColor = 'text-[#344054]',
  className = ''
}) => {
  const sizeClasses = {
    sm: 'h-12 w-12',
    md: 'h-16 w-16 sm:h-16 sm:w-16 md:h-18 md:w-18',
    lg: 'h-20 w-20'
  }

  const iconSizeClasses = {
    sm: 'size-4',
    md: 'size-6',
    lg: 'size-8'
  }

  return (
    <div className={`flex flex-col gap-2 items-center justify-center ${className}`}>
      <div className={`flex items-center justify-center rounded-full ${sizeClasses[size]} ${backgroundColor}`}>
        <Icon className={`${iconColor} ${iconSizeClasses[size]}`} />
      </div>
      <span className={`${labelColor} text-sm font-medium text-center`}>
        {label}
      </span>
    </div>
  )
}

export default Badge