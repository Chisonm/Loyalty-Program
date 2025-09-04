import AppLayout from "./layout/app-layout";
import ProgressBar from "./components/ProgressBar";
import Badge from "./components/Badge";
import AchievementItem from "./components/AchievementItem";
import { Setting, ShoppingBag } from "iconsax-reactjs";

function App() {
  return (
    <AppLayout>
      <div className="mx-auto">
        <div className="mb-6 sm:mb-8">
          <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
            Badge & Achievement Overview
          </h1>
          <p className="text-gray-600">Welcome to your dashboard</p>
        </div>
        {/* achievements & badge grid */}
        <div className="grid sm:grid-cols-3 gap-6 sm:gap-8 md:gap-10 lg:gap-12 xl:gap-14 items-start">
          <div className="col-span-2">
            <div className="bg-primary-green py-6 px-4 rounded-lg relative">
              <div className="pointer-events-none absolute inset-0 z-10 h-full w-full bg-[url('/public/content.svg')] bg-contain bg-right bg-no-repeat"></div>
              <div className="flex justify-between items-center">
                <span className="text-white text-2xl font-bold">
                  First Purchase
                </span>
                <span className="text-custom-border-color text-base font-medium">
                  1/5 achievements
                </span>
              </div>
              {/* progress bar */}
              <ProgressBar
                value={1}
                max={5}
                className="mt-4"
                color="bg-white"
                backgroundColor="bg-gray-50/40"
                animated={true}
              />
            </div>

            <div className="text-black border custom-border-color py-6 px-4 w-full rounded-lg relative mt-10">
              <h1 className="text-2xl font-bold mb-6">Achievements</h1>
              <div className="flex flex-col gap-6">
                <AchievementItem
                  icon={ShoppingBag}
                  title="First Purchase"
                  description="Complete your very first purchase"
                  value={1}
                  max={5}
                />

                 <AchievementItem
                  icon={ShoppingBag}
                  title="First Purchase"
                  description="Complete your very first purchase"
                  value={2}
                  max={5}
                />

                 <AchievementItem
                  icon={ShoppingBag}
                  title="First Purchase"
                  description="Complete your very first purchase"
                  value={3}
                  max={5}
                />
              </div>
            </div>
          </div>
          <div className="col-span-1">
            <div className="border custom-border-color py-6 px-4 w-full rounded-lg relative">
              <h1 className="text-2xl font-bold mb-6 text-black">Badges</h1>
              <div className="grid sm:grid-cols-3 gap-6 sm:gap-3 md:gap-4 lg:gap-8 xl:gap-10 w-full">
                <Badge icon={Setting} label="Bronze" />
                <Badge icon={Setting} label="Silver" />
                <Badge icon={Setting} label="Gold" />
                <Badge icon={Setting} label="Platinum" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}

export default App;
