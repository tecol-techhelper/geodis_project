import {
  __export
} from "./chunk-PZ5AY32C.js";

// node_modules/lucide/dist/esm/defaultAttributes.js
var defaultAttributes = {
  xmlns: "http://www.w3.org/2000/svg",
  width: 24,
  height: 24,
  viewBox: "0 0 24 24",
  fill: "none",
  stroke: "currentColor",
  "stroke-width": 2,
  "stroke-linecap": "round",
  "stroke-linejoin": "round"
};

// node_modules/lucide/dist/esm/createElement.js
var createSVGElement = ([tag, attrs, children]) => {
  const element = document.createElementNS("http://www.w3.org/2000/svg", tag);
  Object.keys(attrs).forEach((name) => {
    element.setAttribute(name, String(attrs[name]));
  });
  if (children == null ? void 0 : children.length) {
    children.forEach((child) => {
      const childElement = createSVGElement(child);
      element.appendChild(childElement);
    });
  }
  return element;
};
var createElement = (iconNode, customAttrs = {}) => {
  const tag = "svg";
  const attrs = {
    ...defaultAttributes,
    ...customAttrs
  };
  return createSVGElement([tag, attrs, iconNode]);
};

// node_modules/lucide/dist/esm/replaceElement.js
var getAttrs = (element) => Array.from(element.attributes).reduce((attrs, attr) => {
  attrs[attr.name] = attr.value;
  return attrs;
}, {});
var getClassNames = (attrs) => {
  if (typeof attrs === "string") return attrs;
  if (!attrs || !attrs.class) return "";
  if (attrs.class && typeof attrs.class === "string") {
    return attrs.class.split(" ");
  }
  if (attrs.class && Array.isArray(attrs.class)) {
    return attrs.class;
  }
  return "";
};
var combineClassNames = (arrayOfClassnames) => {
  const classNameArray = arrayOfClassnames.flatMap(getClassNames);
  return classNameArray.map((classItem) => classItem.trim()).filter(Boolean).filter((value, index, self) => self.indexOf(value) === index).join(" ");
};
var toPascalCase = (string) => string.replace(/(\w)(\w*)(_|-|\s*)/g, (g0, g1, g2) => g1.toUpperCase() + g2.toLowerCase());
var replaceElement = (element, { nameAttr, icons, attrs }) => {
  var _a;
  const iconName = element.getAttribute(nameAttr);
  if (iconName == null) return;
  const ComponentName = toPascalCase(iconName);
  const iconNode = icons[ComponentName];
  if (!iconNode) {
    return console.warn(
      `${element.outerHTML} icon name was not found in the provided icons object.`
    );
  }
  const elementAttrs = getAttrs(element);
  const iconAttrs = {
    ...defaultAttributes,
    "data-lucide": iconName,
    ...attrs,
    ...elementAttrs
  };
  const classNames = combineClassNames(["lucide", `lucide-${iconName}`, elementAttrs, attrs]);
  if (classNames) {
    Object.assign(iconAttrs, {
      class: classNames
    });
  }
  const svgElement = createElement(iconNode, iconAttrs);
  return (_a = element.parentNode) == null ? void 0 : _a.replaceChild(svgElement, element);
};

// node_modules/lucide/dist/esm/iconsAndAliases.js
var iconsAndAliases_exports = {};
__export(iconsAndAliases_exports, {
  AArrowDown: () => AArrowDown,
  AArrowUp: () => AArrowUp,
  ALargeSmall: () => ALargeSmall,
  Accessibility: () => Accessibility,
  Activity: () => Activity,
  ActivitySquare: () => SquareActivity,
  AirVent: () => AirVent,
  Airplay: () => Airplay,
  AlarmCheck: () => AlarmClockCheck,
  AlarmClock: () => AlarmClock,
  AlarmClockCheck: () => AlarmClockCheck,
  AlarmClockMinus: () => AlarmClockMinus,
  AlarmClockOff: () => AlarmClockOff,
  AlarmClockPlus: () => AlarmClockPlus,
  AlarmMinus: () => AlarmClockMinus,
  AlarmPlus: () => AlarmClockPlus,
  AlarmSmoke: () => AlarmSmoke,
  Album: () => Album,
  AlertCircle: () => CircleAlert,
  AlertOctagon: () => OctagonAlert,
  AlertTriangle: () => TriangleAlert,
  AlignCenter: () => AlignCenter,
  AlignCenterHorizontal: () => AlignCenterHorizontal,
  AlignCenterVertical: () => AlignCenterVertical,
  AlignEndHorizontal: () => AlignEndHorizontal,
  AlignEndVertical: () => AlignEndVertical,
  AlignHorizontalDistributeCenter: () => AlignHorizontalDistributeCenter,
  AlignHorizontalDistributeEnd: () => AlignHorizontalDistributeEnd,
  AlignHorizontalDistributeStart: () => AlignHorizontalDistributeStart,
  AlignHorizontalJustifyCenter: () => AlignHorizontalJustifyCenter,
  AlignHorizontalJustifyEnd: () => AlignHorizontalJustifyEnd,
  AlignHorizontalJustifyStart: () => AlignHorizontalJustifyStart,
  AlignHorizontalSpaceAround: () => AlignHorizontalSpaceAround,
  AlignHorizontalSpaceBetween: () => AlignHorizontalSpaceBetween,
  AlignJustify: () => AlignJustify,
  AlignLeft: () => AlignLeft,
  AlignRight: () => AlignRight,
  AlignStartHorizontal: () => AlignStartHorizontal,
  AlignStartVertical: () => AlignStartVertical,
  AlignVerticalDistributeCenter: () => AlignVerticalDistributeCenter,
  AlignVerticalDistributeEnd: () => AlignVerticalDistributeEnd,
  AlignVerticalDistributeStart: () => AlignVerticalDistributeStart,
  AlignVerticalJustifyCenter: () => AlignVerticalJustifyCenter,
  AlignVerticalJustifyEnd: () => AlignVerticalJustifyEnd,
  AlignVerticalJustifyStart: () => AlignVerticalJustifyStart,
  AlignVerticalSpaceAround: () => AlignVerticalSpaceAround,
  AlignVerticalSpaceBetween: () => AlignVerticalSpaceBetween,
  Ambulance: () => Ambulance,
  Ampersand: () => Ampersand,
  Ampersands: () => Ampersands,
  Amphora: () => Amphora,
  Anchor: () => Anchor,
  Angry: () => Angry,
  Annoyed: () => Annoyed,
  Antenna: () => Antenna,
  Anvil: () => Anvil,
  Aperture: () => Aperture,
  AppWindow: () => AppWindow,
  AppWindowMac: () => AppWindowMac,
  Apple: () => Apple,
  Archive: () => Archive,
  ArchiveRestore: () => ArchiveRestore,
  ArchiveX: () => ArchiveX,
  AreaChart: () => ChartArea,
  Armchair: () => Armchair,
  ArrowBigDown: () => ArrowBigDown,
  ArrowBigDownDash: () => ArrowBigDownDash,
  ArrowBigLeft: () => ArrowBigLeft,
  ArrowBigLeftDash: () => ArrowBigLeftDash,
  ArrowBigRight: () => ArrowBigRight,
  ArrowBigRightDash: () => ArrowBigRightDash,
  ArrowBigUp: () => ArrowBigUp,
  ArrowBigUpDash: () => ArrowBigUpDash,
  ArrowDown: () => ArrowDown,
  ArrowDown01: () => ArrowDown01,
  ArrowDown10: () => ArrowDown10,
  ArrowDownAZ: () => ArrowDownAZ,
  ArrowDownAz: () => ArrowDownAZ,
  ArrowDownCircle: () => CircleArrowDown,
  ArrowDownFromLine: () => ArrowDownFromLine,
  ArrowDownLeft: () => ArrowDownLeft,
  ArrowDownLeftFromCircle: () => CircleArrowOutDownLeft,
  ArrowDownLeftFromSquare: () => SquareArrowOutDownLeft,
  ArrowDownLeftSquare: () => SquareArrowDownLeft,
  ArrowDownNarrowWide: () => ArrowDownNarrowWide,
  ArrowDownRight: () => ArrowDownRight,
  ArrowDownRightFromCircle: () => CircleArrowOutDownRight,
  ArrowDownRightFromSquare: () => SquareArrowOutDownRight,
  ArrowDownRightSquare: () => SquareArrowDownRight,
  ArrowDownSquare: () => SquareArrowDown,
  ArrowDownToDot: () => ArrowDownToDot,
  ArrowDownToLine: () => ArrowDownToLine,
  ArrowDownUp: () => ArrowDownUp,
  ArrowDownWideNarrow: () => ArrowDownWideNarrow,
  ArrowDownZA: () => ArrowDownZA,
  ArrowDownZa: () => ArrowDownZA,
  ArrowLeft: () => ArrowLeft,
  ArrowLeftCircle: () => CircleArrowLeft,
  ArrowLeftFromLine: () => ArrowLeftFromLine,
  ArrowLeftRight: () => ArrowLeftRight,
  ArrowLeftSquare: () => SquareArrowLeft,
  ArrowLeftToLine: () => ArrowLeftToLine,
  ArrowRight: () => ArrowRight,
  ArrowRightCircle: () => CircleArrowRight,
  ArrowRightFromLine: () => ArrowRightFromLine,
  ArrowRightLeft: () => ArrowRightLeft,
  ArrowRightSquare: () => SquareArrowRight,
  ArrowRightToLine: () => ArrowRightToLine,
  ArrowUp: () => ArrowUp,
  ArrowUp01: () => ArrowUp01,
  ArrowUp10: () => ArrowUp10,
  ArrowUpAZ: () => ArrowUpAZ,
  ArrowUpAz: () => ArrowUpAZ,
  ArrowUpCircle: () => CircleArrowUp,
  ArrowUpDown: () => ArrowUpDown,
  ArrowUpFromDot: () => ArrowUpFromDot,
  ArrowUpFromLine: () => ArrowUpFromLine,
  ArrowUpLeft: () => ArrowUpLeft,
  ArrowUpLeftFromCircle: () => CircleArrowOutUpLeft,
  ArrowUpLeftFromSquare: () => SquareArrowOutUpLeft,
  ArrowUpLeftSquare: () => SquareArrowUpLeft,
  ArrowUpNarrowWide: () => ArrowUpNarrowWide,
  ArrowUpRight: () => ArrowUpRight,
  ArrowUpRightFromCircle: () => CircleArrowOutUpRight,
  ArrowUpRightFromSquare: () => SquareArrowOutUpRight,
  ArrowUpRightSquare: () => SquareArrowUpRight,
  ArrowUpSquare: () => SquareArrowUp,
  ArrowUpToLine: () => ArrowUpToLine,
  ArrowUpWideNarrow: () => ArrowUpWideNarrow,
  ArrowUpZA: () => ArrowUpZA,
  ArrowUpZa: () => ArrowUpZA,
  ArrowsUpFromLine: () => ArrowsUpFromLine,
  Asterisk: () => Asterisk,
  AsteriskSquare: () => SquareAsterisk,
  AtSign: () => AtSign,
  Atom: () => Atom,
  AudioLines: () => AudioLines,
  AudioWaveform: () => AudioWaveform,
  Award: () => Award,
  Axe: () => Axe,
  Axis3D: () => Axis3d,
  Axis3d: () => Axis3d,
  Baby: () => Baby,
  Backpack: () => Backpack,
  Badge: () => Badge,
  BadgeAlert: () => BadgeAlert,
  BadgeCent: () => BadgeCent,
  BadgeCheck: () => BadgeCheck,
  BadgeDollarSign: () => BadgeDollarSign,
  BadgeEuro: () => BadgeEuro,
  BadgeHelp: () => BadgeHelp,
  BadgeIndianRupee: () => BadgeIndianRupee,
  BadgeInfo: () => BadgeInfo,
  BadgeJapaneseYen: () => BadgeJapaneseYen,
  BadgeMinus: () => BadgeMinus,
  BadgePercent: () => BadgePercent,
  BadgePlus: () => BadgePlus,
  BadgePoundSterling: () => BadgePoundSterling,
  BadgeRussianRuble: () => BadgeRussianRuble,
  BadgeSwissFranc: () => BadgeSwissFranc,
  BadgeX: () => BadgeX,
  BaggageClaim: () => BaggageClaim,
  Ban: () => Ban,
  Banana: () => Banana,
  Bandage: () => Bandage,
  Banknote: () => Banknote,
  BanknoteArrowDown: () => BanknoteArrowDown,
  BanknoteArrowUp: () => BanknoteArrowUp,
  BanknoteX: () => BanknoteX,
  BarChart: () => ChartNoAxesColumnIncreasing,
  BarChart2: () => ChartNoAxesColumn,
  BarChart3: () => ChartColumn,
  BarChart4: () => ChartColumnIncreasing,
  BarChartBig: () => ChartColumnBig,
  BarChartHorizontal: () => ChartBar,
  BarChartHorizontalBig: () => ChartBarBig,
  Barcode: () => Barcode,
  Baseline: () => Baseline,
  Bath: () => Bath,
  Battery: () => Battery,
  BatteryCharging: () => BatteryCharging,
  BatteryFull: () => BatteryFull,
  BatteryLow: () => BatteryLow,
  BatteryMedium: () => BatteryMedium,
  BatteryPlus: () => BatteryPlus,
  BatteryWarning: () => BatteryWarning,
  Beaker: () => Beaker,
  Bean: () => Bean,
  BeanOff: () => BeanOff,
  Bed: () => Bed,
  BedDouble: () => BedDouble,
  BedSingle: () => BedSingle,
  Beef: () => Beef,
  Beer: () => Beer,
  BeerOff: () => BeerOff,
  Bell: () => Bell,
  BellDot: () => BellDot,
  BellElectric: () => BellElectric,
  BellMinus: () => BellMinus,
  BellOff: () => BellOff,
  BellPlus: () => BellPlus,
  BellRing: () => BellRing,
  BetweenHorizonalEnd: () => BetweenHorizontalEnd,
  BetweenHorizonalStart: () => BetweenHorizontalStart,
  BetweenHorizontalEnd: () => BetweenHorizontalEnd,
  BetweenHorizontalStart: () => BetweenHorizontalStart,
  BetweenVerticalEnd: () => BetweenVerticalEnd,
  BetweenVerticalStart: () => BetweenVerticalStart,
  BicepsFlexed: () => BicepsFlexed,
  Bike: () => Bike,
  Binary: () => Binary,
  Binoculars: () => Binoculars,
  Biohazard: () => Biohazard,
  Bird: () => Bird,
  Bitcoin: () => Bitcoin,
  Blend: () => Blend,
  Blinds: () => Blinds,
  Blocks: () => Blocks,
  Bluetooth: () => Bluetooth,
  BluetoothConnected: () => BluetoothConnected,
  BluetoothOff: () => BluetoothOff,
  BluetoothSearching: () => BluetoothSearching,
  Bold: () => Bold,
  Bolt: () => Bolt,
  Bomb: () => Bomb,
  Bone: () => Bone,
  Book: () => Book,
  BookA: () => BookA,
  BookAudio: () => BookAudio,
  BookCheck: () => BookCheck,
  BookCopy: () => BookCopy,
  BookDashed: () => BookDashed,
  BookDown: () => BookDown,
  BookHeadphones: () => BookHeadphones,
  BookHeart: () => BookHeart,
  BookImage: () => BookImage,
  BookKey: () => BookKey,
  BookLock: () => BookLock,
  BookMarked: () => BookMarked,
  BookMinus: () => BookMinus,
  BookOpen: () => BookOpen,
  BookOpenCheck: () => BookOpenCheck,
  BookOpenText: () => BookOpenText,
  BookPlus: () => BookPlus,
  BookTemplate: () => BookDashed,
  BookText: () => BookText,
  BookType: () => BookType,
  BookUp: () => BookUp,
  BookUp2: () => BookUp2,
  BookUser: () => BookUser,
  BookX: () => BookX,
  Bookmark: () => Bookmark,
  BookmarkCheck: () => BookmarkCheck,
  BookmarkMinus: () => BookmarkMinus,
  BookmarkPlus: () => BookmarkPlus,
  BookmarkX: () => BookmarkX,
  BoomBox: () => BoomBox,
  Bot: () => Bot,
  BotMessageSquare: () => BotMessageSquare,
  BotOff: () => BotOff,
  BowArrow: () => BowArrow,
  Box: () => Box,
  BoxSelect: () => SquareDashed,
  Boxes: () => Boxes,
  Braces: () => Braces,
  Brackets: () => Brackets,
  Brain: () => Brain,
  BrainCircuit: () => BrainCircuit,
  BrainCog: () => BrainCog,
  BrickWall: () => BrickWall,
  BrickWallFire: () => BrickWallFire,
  Briefcase: () => Briefcase,
  BriefcaseBusiness: () => BriefcaseBusiness,
  BriefcaseConveyorBelt: () => BriefcaseConveyorBelt,
  BriefcaseMedical: () => BriefcaseMedical,
  BringToFront: () => BringToFront,
  Brush: () => Brush,
  BrushCleaning: () => BrushCleaning,
  Bubbles: () => Bubbles,
  Bug: () => Bug,
  BugOff: () => BugOff,
  BugPlay: () => BugPlay,
  Building: () => Building,
  Building2: () => Building2,
  Bus: () => Bus,
  BusFront: () => BusFront,
  Cable: () => Cable,
  CableCar: () => CableCar,
  Cake: () => Cake,
  CakeSlice: () => CakeSlice,
  Calculator: () => Calculator,
  Calendar: () => Calendar,
  Calendar1: () => Calendar1,
  CalendarArrowDown: () => CalendarArrowDown,
  CalendarArrowUp: () => CalendarArrowUp,
  CalendarCheck: () => CalendarCheck,
  CalendarCheck2: () => CalendarCheck2,
  CalendarClock: () => CalendarClock,
  CalendarCog: () => CalendarCog,
  CalendarDays: () => CalendarDays,
  CalendarFold: () => CalendarFold,
  CalendarHeart: () => CalendarHeart,
  CalendarMinus: () => CalendarMinus,
  CalendarMinus2: () => CalendarMinus2,
  CalendarOff: () => CalendarOff,
  CalendarPlus: () => CalendarPlus,
  CalendarPlus2: () => CalendarPlus2,
  CalendarRange: () => CalendarRange,
  CalendarSearch: () => CalendarSearch,
  CalendarSync: () => CalendarSync,
  CalendarX: () => CalendarX,
  CalendarX2: () => CalendarX2,
  Camera: () => Camera,
  CameraOff: () => CameraOff,
  CandlestickChart: () => ChartCandlestick,
  Candy: () => Candy,
  CandyCane: () => CandyCane,
  CandyOff: () => CandyOff,
  Cannabis: () => Cannabis,
  Captions: () => Captions,
  CaptionsOff: () => CaptionsOff,
  Car: () => Car,
  CarFront: () => CarFront,
  CarTaxiFront: () => CarTaxiFront,
  Caravan: () => Caravan,
  Carrot: () => Carrot,
  CaseLower: () => CaseLower,
  CaseSensitive: () => CaseSensitive,
  CaseUpper: () => CaseUpper,
  CassetteTape: () => CassetteTape,
  Cast: () => Cast,
  Castle: () => Castle,
  Cat: () => Cat,
  Cctv: () => Cctv,
  ChartArea: () => ChartArea,
  ChartBar: () => ChartBar,
  ChartBarBig: () => ChartBarBig,
  ChartBarDecreasing: () => ChartBarDecreasing,
  ChartBarIncreasing: () => ChartBarIncreasing,
  ChartBarStacked: () => ChartBarStacked,
  ChartCandlestick: () => ChartCandlestick,
  ChartColumn: () => ChartColumn,
  ChartColumnBig: () => ChartColumnBig,
  ChartColumnDecreasing: () => ChartColumnDecreasing,
  ChartColumnIncreasing: () => ChartColumnIncreasing,
  ChartColumnStacked: () => ChartColumnStacked,
  ChartGantt: () => ChartGantt,
  ChartLine: () => ChartLine,
  ChartNetwork: () => ChartNetwork,
  ChartNoAxesColumn: () => ChartNoAxesColumn,
  ChartNoAxesColumnDecreasing: () => ChartNoAxesColumnDecreasing,
  ChartNoAxesColumnIncreasing: () => ChartNoAxesColumnIncreasing,
  ChartNoAxesCombined: () => ChartNoAxesCombined,
  ChartNoAxesGantt: () => ChartNoAxesGantt,
  ChartPie: () => ChartPie,
  ChartScatter: () => ChartScatter,
  ChartSpline: () => ChartSpline,
  Check: () => Check,
  CheckCheck: () => CheckCheck,
  CheckCircle: () => CircleCheckBig,
  CheckCircle2: () => CircleCheck,
  CheckSquare: () => SquareCheckBig,
  CheckSquare2: () => SquareCheck,
  ChefHat: () => ChefHat,
  Cherry: () => Cherry,
  ChevronDown: () => ChevronDown,
  ChevronDownCircle: () => CircleChevronDown,
  ChevronDownSquare: () => SquareChevronDown,
  ChevronFirst: () => ChevronFirst,
  ChevronLast: () => ChevronLast,
  ChevronLeft: () => ChevronLeft,
  ChevronLeftCircle: () => CircleChevronLeft,
  ChevronLeftSquare: () => SquareChevronLeft,
  ChevronRight: () => ChevronRight,
  ChevronRightCircle: () => CircleChevronRight,
  ChevronRightSquare: () => SquareChevronRight,
  ChevronUp: () => ChevronUp,
  ChevronUpCircle: () => CircleChevronUp,
  ChevronUpSquare: () => SquareChevronUp,
  ChevronsDown: () => ChevronsDown,
  ChevronsDownUp: () => ChevronsDownUp,
  ChevronsLeft: () => ChevronsLeft,
  ChevronsLeftRight: () => ChevronsLeftRight,
  ChevronsLeftRightEllipsis: () => ChevronsLeftRightEllipsis,
  ChevronsRight: () => ChevronsRight,
  ChevronsRightLeft: () => ChevronsRightLeft,
  ChevronsUp: () => ChevronsUp,
  ChevronsUpDown: () => ChevronsUpDown,
  Chrome: () => Chrome,
  Church: () => Church,
  Cigarette: () => Cigarette,
  CigaretteOff: () => CigaretteOff,
  Circle: () => Circle,
  CircleAlert: () => CircleAlert,
  CircleArrowDown: () => CircleArrowDown,
  CircleArrowLeft: () => CircleArrowLeft,
  CircleArrowOutDownLeft: () => CircleArrowOutDownLeft,
  CircleArrowOutDownRight: () => CircleArrowOutDownRight,
  CircleArrowOutUpLeft: () => CircleArrowOutUpLeft,
  CircleArrowOutUpRight: () => CircleArrowOutUpRight,
  CircleArrowRight: () => CircleArrowRight,
  CircleArrowUp: () => CircleArrowUp,
  CircleCheck: () => CircleCheck,
  CircleCheckBig: () => CircleCheckBig,
  CircleChevronDown: () => CircleChevronDown,
  CircleChevronLeft: () => CircleChevronLeft,
  CircleChevronRight: () => CircleChevronRight,
  CircleChevronUp: () => CircleChevronUp,
  CircleDashed: () => CircleDashed,
  CircleDivide: () => CircleDivide,
  CircleDollarSign: () => CircleDollarSign,
  CircleDot: () => CircleDot,
  CircleDotDashed: () => CircleDotDashed,
  CircleEllipsis: () => CircleEllipsis,
  CircleEqual: () => CircleEqual,
  CircleFadingArrowUp: () => CircleFadingArrowUp,
  CircleFadingPlus: () => CircleFadingPlus,
  CircleGauge: () => CircleGauge,
  CircleHelp: () => CircleHelp,
  CircleMinus: () => CircleMinus,
  CircleOff: () => CircleOff,
  CircleParking: () => CircleParking,
  CircleParkingOff: () => CircleParkingOff,
  CirclePause: () => CirclePause,
  CirclePercent: () => CirclePercent,
  CirclePlay: () => CirclePlay,
  CirclePlus: () => CirclePlus,
  CirclePower: () => CirclePower,
  CircleSlash: () => CircleSlash,
  CircleSlash2: () => CircleSlash2,
  CircleSlashed: () => CircleSlash2,
  CircleSmall: () => CircleSmall,
  CircleStop: () => CircleStop,
  CircleUser: () => CircleUser,
  CircleUserRound: () => CircleUserRound,
  CircleX: () => CircleX,
  CircuitBoard: () => CircuitBoard,
  Citrus: () => Citrus,
  Clapperboard: () => Clapperboard,
  Clipboard: () => Clipboard,
  ClipboardCheck: () => ClipboardCheck,
  ClipboardCopy: () => ClipboardCopy,
  ClipboardEdit: () => ClipboardPen,
  ClipboardList: () => ClipboardList,
  ClipboardMinus: () => ClipboardMinus,
  ClipboardPaste: () => ClipboardPaste,
  ClipboardPen: () => ClipboardPen,
  ClipboardPenLine: () => ClipboardPenLine,
  ClipboardPlus: () => ClipboardPlus,
  ClipboardSignature: () => ClipboardPenLine,
  ClipboardType: () => ClipboardType,
  ClipboardX: () => ClipboardX,
  Clock: () => Clock,
  Clock1: () => Clock1,
  Clock10: () => Clock10,
  Clock11: () => Clock11,
  Clock12: () => Clock12,
  Clock2: () => Clock2,
  Clock3: () => Clock3,
  Clock4: () => Clock4,
  Clock5: () => Clock5,
  Clock6: () => Clock6,
  Clock7: () => Clock7,
  Clock8: () => Clock8,
  Clock9: () => Clock9,
  ClockAlert: () => ClockAlert,
  ClockArrowDown: () => ClockArrowDown,
  ClockArrowUp: () => ClockArrowUp,
  ClockFading: () => ClockFading,
  ClockPlus: () => ClockPlus,
  Cloud: () => Cloud,
  CloudAlert: () => CloudAlert,
  CloudCog: () => CloudCog,
  CloudDownload: () => CloudDownload,
  CloudDrizzle: () => CloudDrizzle,
  CloudFog: () => CloudFog,
  CloudHail: () => CloudHail,
  CloudLightning: () => CloudLightning,
  CloudMoon: () => CloudMoon,
  CloudMoonRain: () => CloudMoonRain,
  CloudOff: () => CloudOff,
  CloudRain: () => CloudRain,
  CloudRainWind: () => CloudRainWind,
  CloudSnow: () => CloudSnow,
  CloudSun: () => CloudSun,
  CloudSunRain: () => CloudSunRain,
  CloudUpload: () => CloudUpload,
  Cloudy: () => Cloudy,
  Clover: () => Clover,
  Club: () => Club,
  Code: () => Code,
  Code2: () => CodeXml,
  CodeSquare: () => SquareCode,
  CodeXml: () => CodeXml,
  Codepen: () => Codepen,
  Codesandbox: () => Codesandbox,
  Coffee: () => Coffee,
  Cog: () => Cog,
  Coins: () => Coins,
  Columns: () => Columns2,
  Columns2: () => Columns2,
  Columns3: () => Columns3,
  Columns3Cog: () => Columns3Cog,
  Columns4: () => Columns4,
  ColumnsSettings: () => Columns3Cog,
  Combine: () => Combine,
  Command: () => Command,
  Compass: () => Compass,
  Component: () => Component,
  Computer: () => Computer,
  ConciergeBell: () => ConciergeBell,
  Cone: () => Cone,
  Construction: () => Construction,
  Contact: () => Contact,
  Contact2: () => ContactRound,
  ContactRound: () => ContactRound,
  Container: () => Container,
  Contrast: () => Contrast,
  Cookie: () => Cookie,
  CookingPot: () => CookingPot,
  Copy: () => Copy,
  CopyCheck: () => CopyCheck,
  CopyMinus: () => CopyMinus,
  CopyPlus: () => CopyPlus,
  CopySlash: () => CopySlash,
  CopyX: () => CopyX,
  Copyleft: () => Copyleft,
  Copyright: () => Copyright,
  CornerDownLeft: () => CornerDownLeft,
  CornerDownRight: () => CornerDownRight,
  CornerLeftDown: () => CornerLeftDown,
  CornerLeftUp: () => CornerLeftUp,
  CornerRightDown: () => CornerRightDown,
  CornerRightUp: () => CornerRightUp,
  CornerUpLeft: () => CornerUpLeft,
  CornerUpRight: () => CornerUpRight,
  Cpu: () => Cpu,
  CreativeCommons: () => CreativeCommons,
  CreditCard: () => CreditCard,
  Croissant: () => Croissant,
  Crop: () => Crop,
  Cross: () => Cross,
  Crosshair: () => Crosshair,
  Crown: () => Crown,
  Cuboid: () => Cuboid,
  CupSoda: () => CupSoda,
  CurlyBraces: () => Braces,
  Currency: () => Currency,
  Cylinder: () => Cylinder,
  Dam: () => Dam,
  Database: () => Database,
  DatabaseBackup: () => DatabaseBackup,
  DatabaseZap: () => DatabaseZap,
  DecimalsArrowLeft: () => DecimalsArrowLeft,
  DecimalsArrowRight: () => DecimalsArrowRight,
  Delete: () => Delete,
  Dessert: () => Dessert,
  Diameter: () => Diameter,
  Diamond: () => Diamond,
  DiamondMinus: () => DiamondMinus,
  DiamondPercent: () => DiamondPercent,
  DiamondPlus: () => DiamondPlus,
  Dice1: () => Dice1,
  Dice2: () => Dice2,
  Dice3: () => Dice3,
  Dice4: () => Dice4,
  Dice5: () => Dice5,
  Dice6: () => Dice6,
  Dices: () => Dices,
  Diff: () => Diff,
  Disc: () => Disc,
  Disc2: () => Disc2,
  Disc3: () => Disc3,
  DiscAlbum: () => DiscAlbum,
  Divide: () => Divide,
  DivideCircle: () => CircleDivide,
  DivideSquare: () => SquareDivide,
  Dna: () => Dna,
  DnaOff: () => DnaOff,
  Dock: () => Dock,
  Dog: () => Dog,
  DollarSign: () => DollarSign,
  Donut: () => Donut,
  DoorClosed: () => DoorClosed,
  DoorClosedLocked: () => DoorClosedLocked,
  DoorOpen: () => DoorOpen,
  Dot: () => Dot,
  DotSquare: () => SquareDot,
  Download: () => Download,
  DownloadCloud: () => CloudDownload,
  DraftingCompass: () => DraftingCompass,
  Drama: () => Drama,
  Dribbble: () => Dribbble,
  Drill: () => Drill,
  Droplet: () => Droplet,
  DropletOff: () => DropletOff,
  Droplets: () => Droplets,
  Drum: () => Drum,
  Drumstick: () => Drumstick,
  Dumbbell: () => Dumbbell,
  Ear: () => Ear,
  EarOff: () => EarOff,
  Earth: () => Earth,
  EarthLock: () => EarthLock,
  Eclipse: () => Eclipse,
  Edit: () => SquarePen,
  Edit2: () => Pen,
  Edit3: () => PenLine,
  Egg: () => Egg,
  EggFried: () => EggFried,
  EggOff: () => EggOff,
  Ellipsis: () => Ellipsis,
  EllipsisVertical: () => EllipsisVertical,
  Equal: () => Equal,
  EqualApproximately: () => EqualApproximately,
  EqualNot: () => EqualNot,
  EqualSquare: () => SquareEqual,
  Eraser: () => Eraser,
  EthernetPort: () => EthernetPort,
  Euro: () => Euro,
  Expand: () => Expand,
  ExternalLink: () => ExternalLink,
  Eye: () => Eye,
  EyeClosed: () => EyeClosed,
  EyeOff: () => EyeOff,
  Facebook: () => Facebook,
  Factory: () => Factory,
  Fan: () => Fan,
  FastForward: () => FastForward,
  Feather: () => Feather,
  Fence: () => Fence,
  FerrisWheel: () => FerrisWheel,
  Figma: () => Figma,
  File: () => File,
  FileArchive: () => FileArchive,
  FileAudio: () => FileAudio,
  FileAudio2: () => FileAudio2,
  FileAxis3D: () => FileAxis3d,
  FileAxis3d: () => FileAxis3d,
  FileBadge: () => FileBadge,
  FileBadge2: () => FileBadge2,
  FileBarChart: () => FileChartColumnIncreasing,
  FileBarChart2: () => FileChartColumn,
  FileBox: () => FileBox,
  FileChartColumn: () => FileChartColumn,
  FileChartColumnIncreasing: () => FileChartColumnIncreasing,
  FileChartLine: () => FileChartLine,
  FileChartPie: () => FileChartPie,
  FileCheck: () => FileCheck,
  FileCheck2: () => FileCheck2,
  FileClock: () => FileClock,
  FileCode: () => FileCode,
  FileCode2: () => FileCode2,
  FileCog: () => FileCog,
  FileCog2: () => FileCog,
  FileDiff: () => FileDiff,
  FileDigit: () => FileDigit,
  FileDown: () => FileDown,
  FileEdit: () => FilePen,
  FileHeart: () => FileHeart,
  FileImage: () => FileImage,
  FileInput: () => FileInput,
  FileJson: () => FileJson,
  FileJson2: () => FileJson2,
  FileKey: () => FileKey,
  FileKey2: () => FileKey2,
  FileLineChart: () => FileChartLine,
  FileLock: () => FileLock,
  FileLock2: () => FileLock2,
  FileMinus: () => FileMinus,
  FileMinus2: () => FileMinus2,
  FileMusic: () => FileMusic,
  FileOutput: () => FileOutput,
  FilePen: () => FilePen,
  FilePenLine: () => FilePenLine,
  FilePieChart: () => FileChartPie,
  FilePlus: () => FilePlus,
  FilePlus2: () => FilePlus2,
  FileQuestion: () => FileQuestion,
  FileScan: () => FileScan,
  FileSearch: () => FileSearch,
  FileSearch2: () => FileSearch2,
  FileSignature: () => FilePenLine,
  FileSliders: () => FileSliders,
  FileSpreadsheet: () => FileSpreadsheet,
  FileStack: () => FileStack,
  FileSymlink: () => FileSymlink,
  FileTerminal: () => FileTerminal,
  FileText: () => FileText,
  FileType: () => FileType,
  FileType2: () => FileType2,
  FileUp: () => FileUp,
  FileUser: () => FileUser,
  FileVideo: () => FileVideo,
  FileVideo2: () => FileVideo2,
  FileVolume: () => FileVolume,
  FileVolume2: () => FileVolume2,
  FileWarning: () => FileWarning,
  FileX: () => FileX,
  FileX2: () => FileX2,
  Files: () => Files,
  Film: () => Film,
  Filter: () => Funnel,
  FilterX: () => FunnelX,
  Fingerprint: () => Fingerprint,
  FireExtinguisher: () => FireExtinguisher,
  Fish: () => Fish,
  FishOff: () => FishOff,
  FishSymbol: () => FishSymbol,
  Flag: () => Flag,
  FlagOff: () => FlagOff,
  FlagTriangleLeft: () => FlagTriangleLeft,
  FlagTriangleRight: () => FlagTriangleRight,
  Flame: () => Flame,
  FlameKindling: () => FlameKindling,
  Flashlight: () => Flashlight,
  FlashlightOff: () => FlashlightOff,
  FlaskConical: () => FlaskConical,
  FlaskConicalOff: () => FlaskConicalOff,
  FlaskRound: () => FlaskRound,
  FlipHorizontal: () => FlipHorizontal,
  FlipHorizontal2: () => FlipHorizontal2,
  FlipVertical: () => FlipVertical,
  FlipVertical2: () => FlipVertical2,
  Flower: () => Flower,
  Flower2: () => Flower2,
  Focus: () => Focus,
  FoldHorizontal: () => FoldHorizontal,
  FoldVertical: () => FoldVertical,
  Folder: () => Folder,
  FolderArchive: () => FolderArchive,
  FolderCheck: () => FolderCheck,
  FolderClock: () => FolderClock,
  FolderClosed: () => FolderClosed,
  FolderCode: () => FolderCode,
  FolderCog: () => FolderCog,
  FolderCog2: () => FolderCog,
  FolderDot: () => FolderDot,
  FolderDown: () => FolderDown,
  FolderEdit: () => FolderPen,
  FolderGit: () => FolderGit,
  FolderGit2: () => FolderGit2,
  FolderHeart: () => FolderHeart,
  FolderInput: () => FolderInput,
  FolderKanban: () => FolderKanban,
  FolderKey: () => FolderKey,
  FolderLock: () => FolderLock,
  FolderMinus: () => FolderMinus,
  FolderOpen: () => FolderOpen,
  FolderOpenDot: () => FolderOpenDot,
  FolderOutput: () => FolderOutput,
  FolderPen: () => FolderPen,
  FolderPlus: () => FolderPlus,
  FolderRoot: () => FolderRoot,
  FolderSearch: () => FolderSearch,
  FolderSearch2: () => FolderSearch2,
  FolderSymlink: () => FolderSymlink,
  FolderSync: () => FolderSync,
  FolderTree: () => FolderTree,
  FolderUp: () => FolderUp,
  FolderX: () => FolderX,
  Folders: () => Folders,
  Footprints: () => Footprints,
  ForkKnife: () => Utensils,
  ForkKnifeCrossed: () => UtensilsCrossed,
  Forklift: () => Forklift,
  FormInput: () => RectangleEllipsis,
  Forward: () => Forward,
  Frame: () => Frame,
  Framer: () => Framer,
  Frown: () => Frown,
  Fuel: () => Fuel,
  Fullscreen: () => Fullscreen,
  FunctionSquare: () => SquareFunction,
  Funnel: () => Funnel,
  FunnelPlus: () => FunnelPlus,
  FunnelX: () => FunnelX,
  GalleryHorizontal: () => GalleryHorizontal,
  GalleryHorizontalEnd: () => GalleryHorizontalEnd,
  GalleryThumbnails: () => GalleryThumbnails,
  GalleryVertical: () => GalleryVertical,
  GalleryVerticalEnd: () => GalleryVerticalEnd,
  Gamepad: () => Gamepad,
  Gamepad2: () => Gamepad2,
  GanttChart: () => ChartNoAxesGantt,
  GanttChartSquare: () => SquareChartGantt,
  Gauge: () => Gauge,
  GaugeCircle: () => CircleGauge,
  Gavel: () => Gavel,
  Gem: () => Gem,
  Ghost: () => Ghost,
  Gift: () => Gift,
  GitBranch: () => GitBranch,
  GitBranchPlus: () => GitBranchPlus,
  GitCommit: () => GitCommitHorizontal,
  GitCommitHorizontal: () => GitCommitHorizontal,
  GitCommitVertical: () => GitCommitVertical,
  GitCompare: () => GitCompare,
  GitCompareArrows: () => GitCompareArrows,
  GitFork: () => GitFork,
  GitGraph: () => GitGraph,
  GitMerge: () => GitMerge,
  GitPullRequest: () => GitPullRequest,
  GitPullRequestArrow: () => GitPullRequestArrow,
  GitPullRequestClosed: () => GitPullRequestClosed,
  GitPullRequestCreate: () => GitPullRequestCreate,
  GitPullRequestCreateArrow: () => GitPullRequestCreateArrow,
  GitPullRequestDraft: () => GitPullRequestDraft,
  Github: () => Github,
  Gitlab: () => Gitlab,
  GlassWater: () => GlassWater,
  Glasses: () => Glasses,
  Globe: () => Globe,
  Globe2: () => Earth,
  GlobeLock: () => GlobeLock,
  Goal: () => Goal,
  Grab: () => Grab,
  GraduationCap: () => GraduationCap,
  Grape: () => Grape,
  Grid: () => Grid3x3,
  Grid2X2: () => Grid2x2,
  Grid2X2Check: () => Grid2x2Check,
  Grid2X2Plus: () => Grid2x2Plus,
  Grid2X2X: () => Grid2x2X,
  Grid2x2: () => Grid2x2,
  Grid2x2Check: () => Grid2x2Check,
  Grid2x2Plus: () => Grid2x2Plus,
  Grid2x2X: () => Grid2x2X,
  Grid3X3: () => Grid3x3,
  Grid3x3: () => Grid3x3,
  Grip: () => Grip,
  GripHorizontal: () => GripHorizontal,
  GripVertical: () => GripVertical,
  Group: () => Group,
  Guitar: () => Guitar,
  Ham: () => Ham,
  Hamburger: () => Hamburger,
  Hammer: () => Hammer,
  Hand: () => Hand,
  HandCoins: () => HandCoins,
  HandHeart: () => HandHeart,
  HandHelping: () => HandHelping,
  HandMetal: () => HandMetal,
  HandPlatter: () => HandPlatter,
  Handshake: () => Handshake,
  HardDrive: () => HardDrive,
  HardDriveDownload: () => HardDriveDownload,
  HardDriveUpload: () => HardDriveUpload,
  HardHat: () => HardHat,
  Hash: () => Hash,
  Haze: () => Haze,
  HdmiPort: () => HdmiPort,
  Heading: () => Heading,
  Heading1: () => Heading1,
  Heading2: () => Heading2,
  Heading3: () => Heading3,
  Heading4: () => Heading4,
  Heading5: () => Heading5,
  Heading6: () => Heading6,
  HeadphoneOff: () => HeadphoneOff,
  Headphones: () => Headphones,
  Headset: () => Headset,
  Heart: () => Heart,
  HeartCrack: () => HeartCrack,
  HeartHandshake: () => HeartHandshake,
  HeartMinus: () => HeartMinus,
  HeartOff: () => HeartOff,
  HeartPlus: () => HeartPlus,
  HeartPulse: () => HeartPulse,
  Heater: () => Heater,
  HelpCircle: () => CircleHelp,
  HelpingHand: () => HandHelping,
  Hexagon: () => Hexagon,
  Highlighter: () => Highlighter,
  History: () => History,
  Home: () => House,
  Hop: () => Hop,
  HopOff: () => HopOff,
  Hospital: () => Hospital,
  Hotel: () => Hotel,
  Hourglass: () => Hourglass,
  House: () => House,
  HousePlug: () => HousePlug,
  HousePlus: () => HousePlus,
  HouseWifi: () => HouseWifi,
  IceCream: () => IceCreamCone,
  IceCream2: () => IceCreamBowl,
  IceCreamBowl: () => IceCreamBowl,
  IceCreamCone: () => IceCreamCone,
  IdCard: () => IdCard,
  Image: () => Image,
  ImageDown: () => ImageDown,
  ImageMinus: () => ImageMinus,
  ImageOff: () => ImageOff,
  ImagePlay: () => ImagePlay,
  ImagePlus: () => ImagePlus,
  ImageUp: () => ImageUp,
  ImageUpscale: () => ImageUpscale,
  Images: () => Images,
  Import: () => Import,
  Inbox: () => Inbox,
  Indent: () => IndentIncrease,
  IndentDecrease: () => IndentDecrease,
  IndentIncrease: () => IndentIncrease,
  IndianRupee: () => IndianRupee,
  Infinity: () => Infinity,
  Info: () => Info,
  Inspect: () => SquareMousePointer,
  InspectionPanel: () => InspectionPanel,
  Instagram: () => Instagram,
  Italic: () => Italic,
  IterationCcw: () => IterationCcw,
  IterationCw: () => IterationCw,
  JapaneseYen: () => JapaneseYen,
  Joystick: () => Joystick,
  Kanban: () => Kanban,
  KanbanSquare: () => SquareKanban,
  KanbanSquareDashed: () => SquareDashedKanban,
  Key: () => Key,
  KeyRound: () => KeyRound,
  KeySquare: () => KeySquare,
  Keyboard: () => Keyboard,
  KeyboardMusic: () => KeyboardMusic,
  KeyboardOff: () => KeyboardOff,
  Lamp: () => Lamp,
  LampCeiling: () => LampCeiling,
  LampDesk: () => LampDesk,
  LampFloor: () => LampFloor,
  LampWallDown: () => LampWallDown,
  LampWallUp: () => LampWallUp,
  LandPlot: () => LandPlot,
  Landmark: () => Landmark,
  Languages: () => Languages,
  Laptop: () => Laptop,
  Laptop2: () => LaptopMinimal,
  LaptopMinimal: () => LaptopMinimal,
  LaptopMinimalCheck: () => LaptopMinimalCheck,
  Lasso: () => Lasso,
  LassoSelect: () => LassoSelect,
  Laugh: () => Laugh,
  Layers: () => Layers,
  Layers2: () => Layers2,
  Layers3: () => Layers,
  Layout: () => PanelsTopLeft,
  LayoutDashboard: () => LayoutDashboard,
  LayoutGrid: () => LayoutGrid,
  LayoutList: () => LayoutList,
  LayoutPanelLeft: () => LayoutPanelLeft,
  LayoutPanelTop: () => LayoutPanelTop,
  LayoutTemplate: () => LayoutTemplate,
  Leaf: () => Leaf,
  LeafyGreen: () => LeafyGreen,
  Lectern: () => Lectern,
  LetterText: () => LetterText,
  Library: () => Library,
  LibraryBig: () => LibraryBig,
  LibrarySquare: () => SquareLibrary,
  LifeBuoy: () => LifeBuoy,
  Ligature: () => Ligature,
  Lightbulb: () => Lightbulb,
  LightbulbOff: () => LightbulbOff,
  LineChart: () => ChartLine,
  Link: () => Link,
  Link2: () => Link2,
  Link2Off: () => Link2Off,
  Linkedin: () => Linkedin,
  List: () => List,
  ListCheck: () => ListCheck,
  ListChecks: () => ListChecks,
  ListCollapse: () => ListCollapse,
  ListEnd: () => ListEnd,
  ListFilter: () => ListFilter,
  ListFilterPlus: () => ListFilterPlus,
  ListMinus: () => ListMinus,
  ListMusic: () => ListMusic,
  ListOrdered: () => ListOrdered,
  ListPlus: () => ListPlus,
  ListRestart: () => ListRestart,
  ListStart: () => ListStart,
  ListTodo: () => ListTodo,
  ListTree: () => ListTree,
  ListVideo: () => ListVideo,
  ListX: () => ListX,
  Loader: () => Loader,
  Loader2: () => LoaderCircle,
  LoaderCircle: () => LoaderCircle,
  LoaderPinwheel: () => LoaderPinwheel,
  Locate: () => Locate,
  LocateFixed: () => LocateFixed,
  LocateOff: () => LocateOff,
  LocationEdit: () => LocationEdit,
  Lock: () => Lock,
  LockKeyhole: () => LockKeyhole,
  LockKeyholeOpen: () => LockKeyholeOpen,
  LockOpen: () => LockOpen,
  LogIn: () => LogIn,
  LogOut: () => LogOut,
  Logs: () => Logs,
  Lollipop: () => Lollipop,
  Luggage: () => Luggage,
  MSquare: () => SquareM,
  Magnet: () => Magnet,
  Mail: () => Mail,
  MailCheck: () => MailCheck,
  MailMinus: () => MailMinus,
  MailOpen: () => MailOpen,
  MailPlus: () => MailPlus,
  MailQuestion: () => MailQuestion,
  MailSearch: () => MailSearch,
  MailWarning: () => MailWarning,
  MailX: () => MailX,
  Mailbox: () => Mailbox,
  Mails: () => Mails,
  Map: () => Map,
  MapPin: () => MapPin,
  MapPinCheck: () => MapPinCheck,
  MapPinCheckInside: () => MapPinCheckInside,
  MapPinHouse: () => MapPinHouse,
  MapPinMinus: () => MapPinMinus,
  MapPinMinusInside: () => MapPinMinusInside,
  MapPinOff: () => MapPinOff,
  MapPinPlus: () => MapPinPlus,
  MapPinPlusInside: () => MapPinPlusInside,
  MapPinX: () => MapPinX,
  MapPinXInside: () => MapPinXInside,
  MapPinned: () => MapPinned,
  MapPlus: () => MapPlus,
  Mars: () => Mars,
  MarsStroke: () => MarsStroke,
  Martini: () => Martini,
  Maximize: () => Maximize,
  Maximize2: () => Maximize2,
  Medal: () => Medal,
  Megaphone: () => Megaphone,
  MegaphoneOff: () => MegaphoneOff,
  Meh: () => Meh,
  MemoryStick: () => MemoryStick,
  Menu: () => Menu,
  MenuSquare: () => SquareMenu,
  Merge: () => Merge,
  MessageCircle: () => MessageCircle,
  MessageCircleCode: () => MessageCircleCode,
  MessageCircleDashed: () => MessageCircleDashed,
  MessageCircleHeart: () => MessageCircleHeart,
  MessageCircleMore: () => MessageCircleMore,
  MessageCircleOff: () => MessageCircleOff,
  MessageCirclePlus: () => MessageCirclePlus,
  MessageCircleQuestion: () => MessageCircleQuestion,
  MessageCircleReply: () => MessageCircleReply,
  MessageCircleWarning: () => MessageCircleWarning,
  MessageCircleX: () => MessageCircleX,
  MessageSquare: () => MessageSquare,
  MessageSquareCode: () => MessageSquareCode,
  MessageSquareDashed: () => MessageSquareDashed,
  MessageSquareDiff: () => MessageSquareDiff,
  MessageSquareDot: () => MessageSquareDot,
  MessageSquareHeart: () => MessageSquareHeart,
  MessageSquareLock: () => MessageSquareLock,
  MessageSquareMore: () => MessageSquareMore,
  MessageSquareOff: () => MessageSquareOff,
  MessageSquarePlus: () => MessageSquarePlus,
  MessageSquareQuote: () => MessageSquareQuote,
  MessageSquareReply: () => MessageSquareReply,
  MessageSquareShare: () => MessageSquareShare,
  MessageSquareText: () => MessageSquareText,
  MessageSquareWarning: () => MessageSquareWarning,
  MessageSquareX: () => MessageSquareX,
  MessagesSquare: () => MessagesSquare,
  Mic: () => Mic,
  Mic2: () => MicVocal,
  MicOff: () => MicOff,
  MicVocal: () => MicVocal,
  Microchip: () => Microchip,
  Microscope: () => Microscope,
  Microwave: () => Microwave,
  Milestone: () => Milestone,
  Milk: () => Milk,
  MilkOff: () => MilkOff,
  Minimize: () => Minimize,
  Minimize2: () => Minimize2,
  Minus: () => Minus,
  MinusCircle: () => CircleMinus,
  MinusSquare: () => SquareMinus,
  Monitor: () => Monitor,
  MonitorCheck: () => MonitorCheck,
  MonitorCog: () => MonitorCog,
  MonitorDot: () => MonitorDot,
  MonitorDown: () => MonitorDown,
  MonitorOff: () => MonitorOff,
  MonitorPause: () => MonitorPause,
  MonitorPlay: () => MonitorPlay,
  MonitorSmartphone: () => MonitorSmartphone,
  MonitorSpeaker: () => MonitorSpeaker,
  MonitorStop: () => MonitorStop,
  MonitorUp: () => MonitorUp,
  MonitorX: () => MonitorX,
  Moon: () => Moon,
  MoonStar: () => MoonStar,
  MoreHorizontal: () => Ellipsis,
  MoreVertical: () => EllipsisVertical,
  Mountain: () => Mountain,
  MountainSnow: () => MountainSnow,
  Mouse: () => Mouse,
  MouseOff: () => MouseOff,
  MousePointer: () => MousePointer,
  MousePointer2: () => MousePointer2,
  MousePointerBan: () => MousePointerBan,
  MousePointerClick: () => MousePointerClick,
  MousePointerSquareDashed: () => SquareDashedMousePointer,
  Move: () => Move,
  Move3D: () => Move3d,
  Move3d: () => Move3d,
  MoveDiagonal: () => MoveDiagonal,
  MoveDiagonal2: () => MoveDiagonal2,
  MoveDown: () => MoveDown,
  MoveDownLeft: () => MoveDownLeft,
  MoveDownRight: () => MoveDownRight,
  MoveHorizontal: () => MoveHorizontal,
  MoveLeft: () => MoveLeft,
  MoveRight: () => MoveRight,
  MoveUp: () => MoveUp,
  MoveUpLeft: () => MoveUpLeft,
  MoveUpRight: () => MoveUpRight,
  MoveVertical: () => MoveVertical,
  Music: () => Music,
  Music2: () => Music2,
  Music3: () => Music3,
  Music4: () => Music4,
  Navigation: () => Navigation,
  Navigation2: () => Navigation2,
  Navigation2Off: () => Navigation2Off,
  NavigationOff: () => NavigationOff,
  Network: () => Network,
  Newspaper: () => Newspaper,
  Nfc: () => Nfc,
  NonBinary: () => NonBinary,
  Notebook: () => Notebook,
  NotebookPen: () => NotebookPen,
  NotebookTabs: () => NotebookTabs,
  NotebookText: () => NotebookText,
  NotepadText: () => NotepadText,
  NotepadTextDashed: () => NotepadTextDashed,
  Nut: () => Nut,
  NutOff: () => NutOff,
  Octagon: () => Octagon,
  OctagonAlert: () => OctagonAlert,
  OctagonMinus: () => OctagonMinus,
  OctagonPause: () => OctagonPause,
  OctagonX: () => OctagonX,
  Omega: () => Omega,
  Option: () => Option,
  Orbit: () => Orbit,
  Origami: () => Origami,
  Outdent: () => IndentDecrease,
  Package: () => Package,
  Package2: () => Package2,
  PackageCheck: () => PackageCheck,
  PackageMinus: () => PackageMinus,
  PackageOpen: () => PackageOpen,
  PackagePlus: () => PackagePlus,
  PackageSearch: () => PackageSearch,
  PackageX: () => PackageX,
  PaintBucket: () => PaintBucket,
  PaintRoller: () => PaintRoller,
  Paintbrush: () => Paintbrush,
  Paintbrush2: () => PaintbrushVertical,
  PaintbrushVertical: () => PaintbrushVertical,
  Palette: () => Palette,
  Palmtree: () => TreePalm,
  PanelBottom: () => PanelBottom,
  PanelBottomClose: () => PanelBottomClose,
  PanelBottomDashed: () => PanelBottomDashed,
  PanelBottomInactive: () => PanelBottomDashed,
  PanelBottomOpen: () => PanelBottomOpen,
  PanelLeft: () => PanelLeft,
  PanelLeftClose: () => PanelLeftClose,
  PanelLeftDashed: () => PanelLeftDashed,
  PanelLeftInactive: () => PanelLeftDashed,
  PanelLeftOpen: () => PanelLeftOpen,
  PanelRight: () => PanelRight,
  PanelRightClose: () => PanelRightClose,
  PanelRightDashed: () => PanelRightDashed,
  PanelRightInactive: () => PanelRightDashed,
  PanelRightOpen: () => PanelRightOpen,
  PanelTop: () => PanelTop,
  PanelTopClose: () => PanelTopClose,
  PanelTopDashed: () => PanelTopDashed,
  PanelTopInactive: () => PanelTopDashed,
  PanelTopOpen: () => PanelTopOpen,
  PanelsLeftBottom: () => PanelsLeftBottom,
  PanelsLeftRight: () => Columns3,
  PanelsRightBottom: () => PanelsRightBottom,
  PanelsTopBottom: () => Rows3,
  PanelsTopLeft: () => PanelsTopLeft,
  Paperclip: () => Paperclip,
  Parentheses: () => Parentheses,
  ParkingCircle: () => CircleParking,
  ParkingCircleOff: () => CircleParkingOff,
  ParkingMeter: () => ParkingMeter,
  ParkingSquare: () => SquareParking,
  ParkingSquareOff: () => SquareParkingOff,
  PartyPopper: () => PartyPopper,
  Pause: () => Pause,
  PauseCircle: () => CirclePause,
  PauseOctagon: () => OctagonPause,
  PawPrint: () => PawPrint,
  PcCase: () => PcCase,
  Pen: () => Pen,
  PenBox: () => SquarePen,
  PenLine: () => PenLine,
  PenOff: () => PenOff,
  PenSquare: () => SquarePen,
  PenTool: () => PenTool,
  Pencil: () => Pencil,
  PencilLine: () => PencilLine,
  PencilOff: () => PencilOff,
  PencilRuler: () => PencilRuler,
  Pentagon: () => Pentagon,
  Percent: () => Percent,
  PercentCircle: () => CirclePercent,
  PercentDiamond: () => DiamondPercent,
  PercentSquare: () => SquarePercent,
  PersonStanding: () => PersonStanding,
  PhilippinePeso: () => PhilippinePeso,
  Phone: () => Phone,
  PhoneCall: () => PhoneCall,
  PhoneForwarded: () => PhoneForwarded,
  PhoneIncoming: () => PhoneIncoming,
  PhoneMissed: () => PhoneMissed,
  PhoneOff: () => PhoneOff,
  PhoneOutgoing: () => PhoneOutgoing,
  Pi: () => Pi,
  PiSquare: () => SquarePi,
  Piano: () => Piano,
  Pickaxe: () => Pickaxe,
  PictureInPicture: () => PictureInPicture,
  PictureInPicture2: () => PictureInPicture2,
  PieChart: () => ChartPie,
  PiggyBank: () => PiggyBank,
  Pilcrow: () => Pilcrow,
  PilcrowLeft: () => PilcrowLeft,
  PilcrowRight: () => PilcrowRight,
  PilcrowSquare: () => SquarePilcrow,
  Pill: () => Pill,
  PillBottle: () => PillBottle,
  Pin: () => Pin,
  PinOff: () => PinOff,
  Pipette: () => Pipette,
  Pizza: () => Pizza,
  Plane: () => Plane,
  PlaneLanding: () => PlaneLanding,
  PlaneTakeoff: () => PlaneTakeoff,
  Play: () => Play,
  PlayCircle: () => CirclePlay,
  PlaySquare: () => SquarePlay,
  Plug: () => Plug,
  Plug2: () => Plug2,
  PlugZap: () => PlugZap,
  PlugZap2: () => PlugZap,
  Plus: () => Plus,
  PlusCircle: () => CirclePlus,
  PlusSquare: () => SquarePlus,
  Pocket: () => Pocket,
  PocketKnife: () => PocketKnife,
  Podcast: () => Podcast,
  Pointer: () => Pointer,
  PointerOff: () => PointerOff,
  Popcorn: () => Popcorn,
  Popsicle: () => Popsicle,
  PoundSterling: () => PoundSterling,
  Power: () => Power,
  PowerCircle: () => CirclePower,
  PowerOff: () => PowerOff,
  PowerSquare: () => SquarePower,
  Presentation: () => Presentation,
  Printer: () => Printer,
  PrinterCheck: () => PrinterCheck,
  Projector: () => Projector,
  Proportions: () => Proportions,
  Puzzle: () => Puzzle,
  Pyramid: () => Pyramid,
  QrCode: () => QrCode,
  Quote: () => Quote,
  Rabbit: () => Rabbit,
  Radar: () => Radar,
  Radiation: () => Radiation,
  Radical: () => Radical,
  Radio: () => Radio,
  RadioReceiver: () => RadioReceiver,
  RadioTower: () => RadioTower,
  Radius: () => Radius,
  RailSymbol: () => RailSymbol,
  Rainbow: () => Rainbow,
  Rat: () => Rat,
  Ratio: () => Ratio,
  Receipt: () => Receipt,
  ReceiptCent: () => ReceiptCent,
  ReceiptEuro: () => ReceiptEuro,
  ReceiptIndianRupee: () => ReceiptIndianRupee,
  ReceiptJapaneseYen: () => ReceiptJapaneseYen,
  ReceiptPoundSterling: () => ReceiptPoundSterling,
  ReceiptRussianRuble: () => ReceiptRussianRuble,
  ReceiptSwissFranc: () => ReceiptSwissFranc,
  ReceiptText: () => ReceiptText,
  RectangleEllipsis: () => RectangleEllipsis,
  RectangleGoggles: () => RectangleGoggles,
  RectangleHorizontal: () => RectangleHorizontal,
  RectangleVertical: () => RectangleVertical,
  Recycle: () => Recycle,
  Redo: () => Redo,
  Redo2: () => Redo2,
  RedoDot: () => RedoDot,
  RefreshCcw: () => RefreshCcw,
  RefreshCcwDot: () => RefreshCcwDot,
  RefreshCw: () => RefreshCw,
  RefreshCwOff: () => RefreshCwOff,
  Refrigerator: () => Refrigerator,
  Regex: () => Regex,
  RemoveFormatting: () => RemoveFormatting,
  Repeat: () => Repeat,
  Repeat1: () => Repeat1,
  Repeat2: () => Repeat2,
  Replace: () => Replace,
  ReplaceAll: () => ReplaceAll,
  Reply: () => Reply,
  ReplyAll: () => ReplyAll,
  Rewind: () => Rewind,
  Ribbon: () => Ribbon,
  Rocket: () => Rocket,
  RockingChair: () => RockingChair,
  RollerCoaster: () => RollerCoaster,
  Rotate3D: () => Rotate3d,
  Rotate3d: () => Rotate3d,
  RotateCcw: () => RotateCcw,
  RotateCcwKey: () => RotateCcwKey,
  RotateCcwSquare: () => RotateCcwSquare,
  RotateCw: () => RotateCw,
  RotateCwSquare: () => RotateCwSquare,
  Route: () => Route,
  RouteOff: () => RouteOff,
  Router: () => Router,
  Rows: () => Rows2,
  Rows2: () => Rows2,
  Rows3: () => Rows3,
  Rows4: () => Rows4,
  Rss: () => Rss,
  Ruler: () => Ruler,
  RulerDimensionLine: () => RulerDimensionLine,
  RussianRuble: () => RussianRuble,
  Sailboat: () => Sailboat,
  Salad: () => Salad,
  Sandwich: () => Sandwich,
  Satellite: () => Satellite,
  SatelliteDish: () => SatelliteDish,
  SaudiRiyal: () => SaudiRiyal,
  Save: () => Save,
  SaveAll: () => SaveAll,
  SaveOff: () => SaveOff,
  Scale: () => Scale,
  Scale3D: () => Scale3d,
  Scale3d: () => Scale3d,
  Scaling: () => Scaling,
  Scan: () => Scan,
  ScanBarcode: () => ScanBarcode,
  ScanEye: () => ScanEye,
  ScanFace: () => ScanFace,
  ScanHeart: () => ScanHeart,
  ScanLine: () => ScanLine,
  ScanQrCode: () => ScanQrCode,
  ScanSearch: () => ScanSearch,
  ScanText: () => ScanText,
  ScatterChart: () => ChartScatter,
  School: () => School,
  School2: () => University,
  Scissors: () => Scissors,
  ScissorsLineDashed: () => ScissorsLineDashed,
  ScissorsSquare: () => SquareScissors,
  ScissorsSquareDashedBottom: () => SquareBottomDashedScissors,
  ScreenShare: () => ScreenShare,
  ScreenShareOff: () => ScreenShareOff,
  Scroll: () => Scroll,
  ScrollText: () => ScrollText,
  Search: () => Search,
  SearchCheck: () => SearchCheck,
  SearchCode: () => SearchCode,
  SearchSlash: () => SearchSlash,
  SearchX: () => SearchX,
  Section: () => Section,
  Send: () => Send,
  SendHorizonal: () => SendHorizontal,
  SendHorizontal: () => SendHorizontal,
  SendToBack: () => SendToBack,
  SeparatorHorizontal: () => SeparatorHorizontal,
  SeparatorVertical: () => SeparatorVertical,
  Server: () => Server,
  ServerCog: () => ServerCog,
  ServerCrash: () => ServerCrash,
  ServerOff: () => ServerOff,
  Settings: () => Settings,
  Settings2: () => Settings2,
  Shapes: () => Shapes,
  Share: () => Share,
  Share2: () => Share2,
  Sheet: () => Sheet,
  Shell: () => Shell,
  Shield: () => Shield,
  ShieldAlert: () => ShieldAlert,
  ShieldBan: () => ShieldBan,
  ShieldCheck: () => ShieldCheck,
  ShieldClose: () => ShieldX,
  ShieldEllipsis: () => ShieldEllipsis,
  ShieldHalf: () => ShieldHalf,
  ShieldMinus: () => ShieldMinus,
  ShieldOff: () => ShieldOff,
  ShieldPlus: () => ShieldPlus,
  ShieldQuestion: () => ShieldQuestion,
  ShieldUser: () => ShieldUser,
  ShieldX: () => ShieldX,
  Ship: () => Ship,
  ShipWheel: () => ShipWheel,
  Shirt: () => Shirt,
  ShoppingBag: () => ShoppingBag,
  ShoppingBasket: () => ShoppingBasket,
  ShoppingCart: () => ShoppingCart,
  Shovel: () => Shovel,
  ShowerHead: () => ShowerHead,
  Shredder: () => Shredder,
  Shrimp: () => Shrimp,
  Shrink: () => Shrink,
  Shrub: () => Shrub,
  Shuffle: () => Shuffle,
  Sidebar: () => PanelLeft,
  SidebarClose: () => PanelLeftClose,
  SidebarOpen: () => PanelLeftOpen,
  Sigma: () => Sigma,
  SigmaSquare: () => SquareSigma,
  Signal: () => Signal,
  SignalHigh: () => SignalHigh,
  SignalLow: () => SignalLow,
  SignalMedium: () => SignalMedium,
  SignalZero: () => SignalZero,
  Signature: () => Signature,
  Signpost: () => Signpost,
  SignpostBig: () => SignpostBig,
  Siren: () => Siren,
  SkipBack: () => SkipBack,
  SkipForward: () => SkipForward,
  Skull: () => Skull,
  Slack: () => Slack,
  Slash: () => Slash,
  SlashSquare: () => SquareSlash,
  Slice: () => Slice,
  Sliders: () => SlidersVertical,
  SlidersHorizontal: () => SlidersHorizontal,
  SlidersVertical: () => SlidersVertical,
  Smartphone: () => Smartphone,
  SmartphoneCharging: () => SmartphoneCharging,
  SmartphoneNfc: () => SmartphoneNfc,
  Smile: () => Smile,
  SmilePlus: () => SmilePlus,
  Snail: () => Snail,
  Snowflake: () => Snowflake,
  SoapDispenserDroplet: () => SoapDispenserDroplet,
  Sofa: () => Sofa,
  SortAsc: () => ArrowUpNarrowWide,
  SortDesc: () => ArrowDownWideNarrow,
  Soup: () => Soup,
  Space: () => Space,
  Spade: () => Spade,
  Sparkle: () => Sparkle,
  Sparkles: () => Sparkles,
  Speaker: () => Speaker,
  Speech: () => Speech,
  SpellCheck: () => SpellCheck,
  SpellCheck2: () => SpellCheck2,
  Spline: () => Spline,
  SplinePointer: () => SplinePointer,
  Split: () => Split,
  SplitSquareHorizontal: () => SquareSplitHorizontal,
  SplitSquareVertical: () => SquareSplitVertical,
  SprayCan: () => SprayCan,
  Sprout: () => Sprout,
  Square: () => Square,
  SquareActivity: () => SquareActivity,
  SquareArrowDown: () => SquareArrowDown,
  SquareArrowDownLeft: () => SquareArrowDownLeft,
  SquareArrowDownRight: () => SquareArrowDownRight,
  SquareArrowLeft: () => SquareArrowLeft,
  SquareArrowOutDownLeft: () => SquareArrowOutDownLeft,
  SquareArrowOutDownRight: () => SquareArrowOutDownRight,
  SquareArrowOutUpLeft: () => SquareArrowOutUpLeft,
  SquareArrowOutUpRight: () => SquareArrowOutUpRight,
  SquareArrowRight: () => SquareArrowRight,
  SquareArrowUp: () => SquareArrowUp,
  SquareArrowUpLeft: () => SquareArrowUpLeft,
  SquareArrowUpRight: () => SquareArrowUpRight,
  SquareAsterisk: () => SquareAsterisk,
  SquareBottomDashedScissors: () => SquareBottomDashedScissors,
  SquareChartGantt: () => SquareChartGantt,
  SquareCheck: () => SquareCheck,
  SquareCheckBig: () => SquareCheckBig,
  SquareChevronDown: () => SquareChevronDown,
  SquareChevronLeft: () => SquareChevronLeft,
  SquareChevronRight: () => SquareChevronRight,
  SquareChevronUp: () => SquareChevronUp,
  SquareCode: () => SquareCode,
  SquareDashed: () => SquareDashed,
  SquareDashedBottom: () => SquareDashedBottom,
  SquareDashedBottomCode: () => SquareDashedBottomCode,
  SquareDashedKanban: () => SquareDashedKanban,
  SquareDashedMousePointer: () => SquareDashedMousePointer,
  SquareDivide: () => SquareDivide,
  SquareDot: () => SquareDot,
  SquareEqual: () => SquareEqual,
  SquareFunction: () => SquareFunction,
  SquareGanttChart: () => SquareChartGantt,
  SquareKanban: () => SquareKanban,
  SquareLibrary: () => SquareLibrary,
  SquareM: () => SquareM,
  SquareMenu: () => SquareMenu,
  SquareMinus: () => SquareMinus,
  SquareMousePointer: () => SquareMousePointer,
  SquareParking: () => SquareParking,
  SquareParkingOff: () => SquareParkingOff,
  SquarePen: () => SquarePen,
  SquarePercent: () => SquarePercent,
  SquarePi: () => SquarePi,
  SquarePilcrow: () => SquarePilcrow,
  SquarePlay: () => SquarePlay,
  SquarePlus: () => SquarePlus,
  SquarePower: () => SquarePower,
  SquareRadical: () => SquareRadical,
  SquareRoundCorner: () => SquareRoundCorner,
  SquareScissors: () => SquareScissors,
  SquareSigma: () => SquareSigma,
  SquareSlash: () => SquareSlash,
  SquareSplitHorizontal: () => SquareSplitHorizontal,
  SquareSplitVertical: () => SquareSplitVertical,
  SquareSquare: () => SquareSquare,
  SquareStack: () => SquareStack,
  SquareTerminal: () => SquareTerminal,
  SquareUser: () => SquareUser,
  SquareUserRound: () => SquareUserRound,
  SquareX: () => SquareX,
  SquaresExclude: () => SquaresExclude,
  SquaresIntersect: () => SquaresIntersect,
  SquaresSubtract: () => SquaresSubtract,
  SquaresUnite: () => SquaresUnite,
  Squircle: () => Squircle,
  Squirrel: () => Squirrel,
  Stamp: () => Stamp,
  Star: () => Star,
  StarHalf: () => StarHalf,
  StarOff: () => StarOff,
  Stars: () => Sparkles,
  StepBack: () => StepBack,
  StepForward: () => StepForward,
  Stethoscope: () => Stethoscope,
  Sticker: () => Sticker,
  StickyNote: () => StickyNote,
  StopCircle: () => CircleStop,
  Store: () => Store,
  StretchHorizontal: () => StretchHorizontal,
  StretchVertical: () => StretchVertical,
  Strikethrough: () => Strikethrough,
  Subscript: () => Subscript,
  Subtitles: () => Captions,
  Sun: () => Sun,
  SunDim: () => SunDim,
  SunMedium: () => SunMedium,
  SunMoon: () => SunMoon,
  SunSnow: () => SunSnow,
  Sunrise: () => Sunrise,
  Sunset: () => Sunset,
  Superscript: () => Superscript,
  SwatchBook: () => SwatchBook,
  SwissFranc: () => SwissFranc,
  SwitchCamera: () => SwitchCamera,
  Sword: () => Sword,
  Swords: () => Swords,
  Syringe: () => Syringe,
  Table: () => Table,
  Table2: () => Table2,
  TableCellsMerge: () => TableCellsMerge,
  TableCellsSplit: () => TableCellsSplit,
  TableColumnsSplit: () => TableColumnsSplit,
  TableConfig: () => Columns3Cog,
  TableOfContents: () => TableOfContents,
  TableProperties: () => TableProperties,
  TableRowsSplit: () => TableRowsSplit,
  Tablet: () => Tablet,
  TabletSmartphone: () => TabletSmartphone,
  Tablets: () => Tablets,
  Tag: () => Tag,
  Tags: () => Tags,
  Tally1: () => Tally1,
  Tally2: () => Tally2,
  Tally3: () => Tally3,
  Tally4: () => Tally4,
  Tally5: () => Tally5,
  Tangent: () => Tangent,
  Target: () => Target,
  Telescope: () => Telescope,
  Tent: () => Tent,
  TentTree: () => TentTree,
  Terminal: () => Terminal,
  TerminalSquare: () => SquareTerminal,
  TestTube: () => TestTube,
  TestTube2: () => TestTubeDiagonal,
  TestTubeDiagonal: () => TestTubeDiagonal,
  TestTubes: () => TestTubes,
  Text: () => Text,
  TextCursor: () => TextCursor,
  TextCursorInput: () => TextCursorInput,
  TextQuote: () => TextQuote,
  TextSearch: () => TextSearch,
  TextSelect: () => TextSelect,
  TextSelection: () => TextSelect,
  Theater: () => Theater,
  Thermometer: () => Thermometer,
  ThermometerSnowflake: () => ThermometerSnowflake,
  ThermometerSun: () => ThermometerSun,
  ThumbsDown: () => ThumbsDown,
  ThumbsUp: () => ThumbsUp,
  Ticket: () => Ticket,
  TicketCheck: () => TicketCheck,
  TicketMinus: () => TicketMinus,
  TicketPercent: () => TicketPercent,
  TicketPlus: () => TicketPlus,
  TicketSlash: () => TicketSlash,
  TicketX: () => TicketX,
  Tickets: () => Tickets,
  TicketsPlane: () => TicketsPlane,
  Timer: () => Timer,
  TimerOff: () => TimerOff,
  TimerReset: () => TimerReset,
  ToggleLeft: () => ToggleLeft,
  ToggleRight: () => ToggleRight,
  Toilet: () => Toilet,
  Tornado: () => Tornado,
  Torus: () => Torus,
  Touchpad: () => Touchpad,
  TouchpadOff: () => TouchpadOff,
  TowerControl: () => TowerControl,
  ToyBrick: () => ToyBrick,
  Tractor: () => Tractor,
  TrafficCone: () => TrafficCone,
  Train: () => TramFront,
  TrainFront: () => TrainFront,
  TrainFrontTunnel: () => TrainFrontTunnel,
  TrainTrack: () => TrainTrack,
  TramFront: () => TramFront,
  Transgender: () => Transgender,
  Trash: () => Trash,
  Trash2: () => Trash2,
  TreeDeciduous: () => TreeDeciduous,
  TreePalm: () => TreePalm,
  TreePine: () => TreePine,
  Trees: () => Trees,
  Trello: () => Trello,
  TrendingDown: () => TrendingDown,
  TrendingUp: () => TrendingUp,
  TrendingUpDown: () => TrendingUpDown,
  Triangle: () => Triangle,
  TriangleAlert: () => TriangleAlert,
  TriangleDashed: () => TriangleDashed,
  TriangleRight: () => TriangleRight,
  Trophy: () => Trophy,
  Truck: () => Truck,
  TruckElectric: () => TruckElectric,
  Turtle: () => Turtle,
  Tv: () => Tv,
  Tv2: () => TvMinimal,
  TvMinimal: () => TvMinimal,
  TvMinimalPlay: () => TvMinimalPlay,
  Twitch: () => Twitch,
  Twitter: () => Twitter,
  Type: () => Type,
  TypeOutline: () => TypeOutline,
  Umbrella: () => Umbrella,
  UmbrellaOff: () => UmbrellaOff,
  Underline: () => Underline,
  Undo: () => Undo,
  Undo2: () => Undo2,
  UndoDot: () => UndoDot,
  UnfoldHorizontal: () => UnfoldHorizontal,
  UnfoldVertical: () => UnfoldVertical,
  Ungroup: () => Ungroup,
  University: () => University,
  Unlink: () => Unlink,
  Unlink2: () => Unlink2,
  Unlock: () => LockOpen,
  UnlockKeyhole: () => LockKeyholeOpen,
  Unplug: () => Unplug,
  Upload: () => Upload,
  UploadCloud: () => CloudUpload,
  Usb: () => Usb,
  User: () => User,
  User2: () => UserRound,
  UserCheck: () => UserCheck,
  UserCheck2: () => UserRoundCheck,
  UserCircle: () => CircleUser,
  UserCircle2: () => CircleUserRound,
  UserCog: () => UserCog,
  UserCog2: () => UserRoundCog,
  UserLock: () => UserLock,
  UserMinus: () => UserMinus,
  UserMinus2: () => UserRoundMinus,
  UserPen: () => UserPen,
  UserPlus: () => UserPlus,
  UserPlus2: () => UserRoundPlus,
  UserRound: () => UserRound,
  UserRoundCheck: () => UserRoundCheck,
  UserRoundCog: () => UserRoundCog,
  UserRoundMinus: () => UserRoundMinus,
  UserRoundPen: () => UserRoundPen,
  UserRoundPlus: () => UserRoundPlus,
  UserRoundSearch: () => UserRoundSearch,
  UserRoundX: () => UserRoundX,
  UserSearch: () => UserSearch,
  UserSquare: () => SquareUser,
  UserSquare2: () => SquareUserRound,
  UserX: () => UserX,
  UserX2: () => UserRoundX,
  Users: () => Users,
  Users2: () => UsersRound,
  UsersRound: () => UsersRound,
  Utensils: () => Utensils,
  UtensilsCrossed: () => UtensilsCrossed,
  UtilityPole: () => UtilityPole,
  Variable: () => Variable,
  Vault: () => Vault,
  Vegan: () => Vegan,
  VenetianMask: () => VenetianMask,
  Venus: () => Venus,
  VenusAndMars: () => VenusAndMars,
  Verified: () => BadgeCheck,
  Vibrate: () => Vibrate,
  VibrateOff: () => VibrateOff,
  Video: () => Video,
  VideoOff: () => VideoOff,
  Videotape: () => Videotape,
  View: () => View,
  Voicemail: () => Voicemail,
  Volleyball: () => Volleyball,
  Volume: () => Volume,
  Volume1: () => Volume1,
  Volume2: () => Volume2,
  VolumeOff: () => VolumeOff,
  VolumeX: () => VolumeX,
  Vote: () => Vote,
  Wallet: () => Wallet,
  Wallet2: () => WalletMinimal,
  WalletCards: () => WalletCards,
  WalletMinimal: () => WalletMinimal,
  Wallpaper: () => Wallpaper,
  Wand: () => Wand,
  Wand2: () => WandSparkles,
  WandSparkles: () => WandSparkles,
  Warehouse: () => Warehouse,
  WashingMachine: () => WashingMachine,
  Watch: () => Watch,
  Waves: () => Waves,
  WavesLadder: () => WavesLadder,
  Waypoints: () => Waypoints,
  Webcam: () => Webcam,
  Webhook: () => Webhook,
  WebhookOff: () => WebhookOff,
  Weight: () => Weight,
  Wheat: () => Wheat,
  WheatOff: () => WheatOff,
  WholeWord: () => WholeWord,
  Wifi: () => Wifi,
  WifiHigh: () => WifiHigh,
  WifiLow: () => WifiLow,
  WifiOff: () => WifiOff,
  WifiPen: () => WifiPen,
  WifiZero: () => WifiZero,
  Wind: () => Wind,
  WindArrowDown: () => WindArrowDown,
  Wine: () => Wine,
  WineOff: () => WineOff,
  Workflow: () => Workflow,
  Worm: () => Worm,
  WrapText: () => WrapText,
  Wrench: () => Wrench,
  X: () => X,
  XCircle: () => CircleX,
  XOctagon: () => OctagonX,
  XSquare: () => SquareX,
  Youtube: () => Youtube,
  Zap: () => Zap,
  ZapOff: () => ZapOff,
  ZoomIn: () => ZoomIn,
  ZoomOut: () => ZoomOut
});

// node_modules/lucide/dist/esm/icons/a-arrow-down.js
var AArrowDown = [
  ["path", { d: "M3.5 13h6" }],
  ["path", { d: "m2 16 4.5-9 4.5 9" }],
  ["path", { d: "M18 7v9" }],
  ["path", { d: "m14 12 4 4 4-4" }]
];

// node_modules/lucide/dist/esm/icons/a-arrow-up.js
var AArrowUp = [
  ["path", { d: "M3.5 13h6" }],
  ["path", { d: "m2 16 4.5-9 4.5 9" }],
  ["path", { d: "M18 16V7" }],
  ["path", { d: "m14 11 4-4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/a-large-small.js
var ALargeSmall = [
  ["path", { d: "M21 14h-5" }],
  ["path", { d: "M16 16v-3.5a2.5 2.5 0 0 1 5 0V16" }],
  ["path", { d: "M4.5 13h6" }],
  ["path", { d: "m3 16 4.5-9 4.5 9" }]
];

// node_modules/lucide/dist/esm/icons/accessibility.js
var Accessibility = [
  ["circle", { cx: "16", cy: "4", r: "1" }],
  ["path", { d: "m18 19 1-7-6 1" }],
  ["path", { d: "m5 8 3-3 5.5 3-2.36 3.5" }],
  ["path", { d: "M4.24 14.5a5 5 0 0 0 6.88 6" }],
  ["path", { d: "M13.76 17.5a5 5 0 0 0-6.88-6" }]
];

// node_modules/lucide/dist/esm/icons/activity.js
var Activity = [
  [
    "path",
    {
      d: "M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/air-vent.js
var AirVent = [
  ["path", { d: "M18 17.5a2.5 2.5 0 1 1-4 2.03V12" }],
  ["path", { d: "M6 12H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M6 8h12" }],
  ["path", { d: "M6.6 15.572A2 2 0 1 0 10 17v-5" }]
];

// node_modules/lucide/dist/esm/icons/airplay.js
var Airplay = [
  ["path", { d: "M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1" }],
  ["path", { d: "m12 15 5 6H7Z" }]
];

// node_modules/lucide/dist/esm/icons/alarm-clock-check.js
var AlarmClockCheck = [
  ["circle", { cx: "12", cy: "13", r: "8" }],
  ["path", { d: "M5 3 2 6" }],
  ["path", { d: "m22 6-3-3" }],
  ["path", { d: "M6.38 18.7 4 21" }],
  ["path", { d: "M17.64 18.67 20 21" }],
  ["path", { d: "m9 13 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/alarm-clock-minus.js
var AlarmClockMinus = [
  ["circle", { cx: "12", cy: "13", r: "8" }],
  ["path", { d: "M5 3 2 6" }],
  ["path", { d: "m22 6-3-3" }],
  ["path", { d: "M6.38 18.7 4 21" }],
  ["path", { d: "M17.64 18.67 20 21" }],
  ["path", { d: "M9 13h6" }]
];

// node_modules/lucide/dist/esm/icons/alarm-clock-off.js
var AlarmClockOff = [
  ["path", { d: "M6.87 6.87a8 8 0 1 0 11.26 11.26" }],
  ["path", { d: "M19.9 14.25a8 8 0 0 0-9.15-9.15" }],
  ["path", { d: "m22 6-3-3" }],
  ["path", { d: "M6.26 18.67 4 21" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M4 4 2 6" }]
];

// node_modules/lucide/dist/esm/icons/alarm-clock-plus.js
var AlarmClockPlus = [
  ["circle", { cx: "12", cy: "13", r: "8" }],
  ["path", { d: "M5 3 2 6" }],
  ["path", { d: "m22 6-3-3" }],
  ["path", { d: "M6.38 18.7 4 21" }],
  ["path", { d: "M17.64 18.67 20 21" }],
  ["path", { d: "M12 10v6" }],
  ["path", { d: "M9 13h6" }]
];

// node_modules/lucide/dist/esm/icons/alarm-clock.js
var AlarmClock = [
  ["circle", { cx: "12", cy: "13", r: "8" }],
  ["path", { d: "M12 9v4l2 2" }],
  ["path", { d: "M5 3 2 6" }],
  ["path", { d: "m22 6-3-3" }],
  ["path", { d: "M6.38 18.7 4 21" }],
  ["path", { d: "M17.64 18.67 20 21" }]
];

// node_modules/lucide/dist/esm/icons/alarm-smoke.js
var AlarmSmoke = [
  ["path", { d: "M11 21c0-2.5 2-2.5 2-5" }],
  ["path", { d: "M16 21c0-2.5 2-2.5 2-5" }],
  ["path", { d: "m19 8-.8 3a1.25 1.25 0 0 1-1.2 1H7a1.25 1.25 0 0 1-1.2-1L5 8" }],
  ["path", { d: "M21 3a1 1 0 0 1 1 1v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a1 1 0 0 1 1-1z" }],
  ["path", { d: "M6 21c0-2.5 2-2.5 2-5" }]
];

// node_modules/lucide/dist/esm/icons/album.js
var Album = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["polyline", { points: "11 3 11 11 14 8 17 11 17 3" }]
];

// node_modules/lucide/dist/esm/icons/align-center-vertical.js
var AlignCenterVertical = [
  ["path", { d: "M12 2v20" }],
  ["path", { d: "M8 10H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h4" }],
  ["path", { d: "M16 10h4a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-4" }],
  ["path", { d: "M8 20H7a2 2 0 0 1-2-2v-2c0-1.1.9-2 2-2h1" }],
  ["path", { d: "M16 14h1a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-1" }]
];

// node_modules/lucide/dist/esm/icons/align-center-horizontal.js
var AlignCenterHorizontal = [
  ["path", { d: "M2 12h20" }],
  ["path", { d: "M10 16v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4" }],
  ["path", { d: "M10 8V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M20 16v1a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-1" }],
  ["path", { d: "M14 8V7c0-1.1.9-2 2-2h2a2 2 0 0 1 2 2v1" }]
];

// node_modules/lucide/dist/esm/icons/align-center.js
var AlignCenter = [
  ["path", { d: "M17 12H7" }],
  ["path", { d: "M19 18H5" }],
  ["path", { d: "M21 6H3" }]
];

// node_modules/lucide/dist/esm/icons/align-end-horizontal.js
var AlignEndHorizontal = [
  ["rect", { width: "6", height: "16", x: "4", y: "2", rx: "2" }],
  ["rect", { width: "6", height: "9", x: "14", y: "9", rx: "2" }],
  ["path", { d: "M22 22H2" }]
];

// node_modules/lucide/dist/esm/icons/align-end-vertical.js
var AlignEndVertical = [
  ["rect", { width: "16", height: "6", x: "2", y: "4", rx: "2" }],
  ["rect", { width: "9", height: "6", x: "9", y: "14", rx: "2" }],
  ["path", { d: "M22 22V2" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-distribute-center.js
var AlignHorizontalDistributeCenter = [
  ["rect", { width: "6", height: "14", x: "4", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "14", y: "7", rx: "2" }],
  ["path", { d: "M17 22v-5" }],
  ["path", { d: "M17 7V2" }],
  ["path", { d: "M7 22v-3" }],
  ["path", { d: "M7 5V2" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-distribute-end.js
var AlignHorizontalDistributeEnd = [
  ["rect", { width: "6", height: "14", x: "4", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "14", y: "7", rx: "2" }],
  ["path", { d: "M10 2v20" }],
  ["path", { d: "M20 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-distribute-start.js
var AlignHorizontalDistributeStart = [
  ["rect", { width: "6", height: "14", x: "4", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "14", y: "7", rx: "2" }],
  ["path", { d: "M4 2v20" }],
  ["path", { d: "M14 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-justify-center.js
var AlignHorizontalJustifyCenter = [
  ["rect", { width: "6", height: "14", x: "2", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "16", y: "7", rx: "2" }],
  ["path", { d: "M12 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-justify-end.js
var AlignHorizontalJustifyEnd = [
  ["rect", { width: "6", height: "14", x: "2", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "12", y: "7", rx: "2" }],
  ["path", { d: "M22 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-justify-start.js
var AlignHorizontalJustifyStart = [
  ["rect", { width: "6", height: "14", x: "6", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "16", y: "7", rx: "2" }],
  ["path", { d: "M2 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-space-around.js
var AlignHorizontalSpaceAround = [
  ["rect", { width: "6", height: "10", x: "9", y: "7", rx: "2" }],
  ["path", { d: "M4 22V2" }],
  ["path", { d: "M20 22V2" }]
];

// node_modules/lucide/dist/esm/icons/align-horizontal-space-between.js
var AlignHorizontalSpaceBetween = [
  ["rect", { width: "6", height: "14", x: "3", y: "5", rx: "2" }],
  ["rect", { width: "6", height: "10", x: "15", y: "7", rx: "2" }],
  ["path", { d: "M3 2v20" }],
  ["path", { d: "M21 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-justify.js
var AlignJustify = [
  ["path", { d: "M3 12h18" }],
  ["path", { d: "M3 18h18" }],
  ["path", { d: "M3 6h18" }]
];

// node_modules/lucide/dist/esm/icons/align-left.js
var AlignLeft = [
  ["path", { d: "M15 12H3" }],
  ["path", { d: "M17 18H3" }],
  ["path", { d: "M21 6H3" }]
];

// node_modules/lucide/dist/esm/icons/align-right.js
var AlignRight = [
  ["path", { d: "M21 12H9" }],
  ["path", { d: "M21 18H7" }],
  ["path", { d: "M21 6H3" }]
];

// node_modules/lucide/dist/esm/icons/align-start-horizontal.js
var AlignStartHorizontal = [
  ["rect", { width: "6", height: "16", x: "4", y: "6", rx: "2" }],
  ["rect", { width: "6", height: "9", x: "14", y: "6", rx: "2" }],
  ["path", { d: "M22 2H2" }]
];

// node_modules/lucide/dist/esm/icons/align-start-vertical.js
var AlignStartVertical = [
  ["rect", { width: "9", height: "6", x: "6", y: "14", rx: "2" }],
  ["rect", { width: "16", height: "6", x: "6", y: "4", rx: "2" }],
  ["path", { d: "M2 2v20" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-distribute-center.js
var AlignVerticalDistributeCenter = [
  ["path", { d: "M22 17h-3" }],
  ["path", { d: "M22 7h-5" }],
  ["path", { d: "M5 17H2" }],
  ["path", { d: "M7 7H2" }],
  ["rect", { x: "5", y: "14", width: "14", height: "6", rx: "2" }],
  ["rect", { x: "7", y: "4", width: "10", height: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-distribute-end.js
var AlignVerticalDistributeEnd = [
  ["rect", { width: "14", height: "6", x: "5", y: "14", rx: "2" }],
  ["rect", { width: "10", height: "6", x: "7", y: "4", rx: "2" }],
  ["path", { d: "M2 20h20" }],
  ["path", { d: "M2 10h20" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-distribute-start.js
var AlignVerticalDistributeStart = [
  ["rect", { width: "14", height: "6", x: "5", y: "14", rx: "2" }],
  ["rect", { width: "10", height: "6", x: "7", y: "4", rx: "2" }],
  ["path", { d: "M2 14h20" }],
  ["path", { d: "M2 4h20" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-justify-center.js
var AlignVerticalJustifyCenter = [
  ["rect", { width: "14", height: "6", x: "5", y: "16", rx: "2" }],
  ["rect", { width: "10", height: "6", x: "7", y: "2", rx: "2" }],
  ["path", { d: "M2 12h20" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-justify-end.js
var AlignVerticalJustifyEnd = [
  ["rect", { width: "14", height: "6", x: "5", y: "12", rx: "2" }],
  ["rect", { width: "10", height: "6", x: "7", y: "2", rx: "2" }],
  ["path", { d: "M2 22h20" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-justify-start.js
var AlignVerticalJustifyStart = [
  ["rect", { width: "14", height: "6", x: "5", y: "16", rx: "2" }],
  ["rect", { width: "10", height: "6", x: "7", y: "6", rx: "2" }],
  ["path", { d: "M2 2h20" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-space-around.js
var AlignVerticalSpaceAround = [
  ["rect", { width: "10", height: "6", x: "7", y: "9", rx: "2" }],
  ["path", { d: "M22 20H2" }],
  ["path", { d: "M22 4H2" }]
];

// node_modules/lucide/dist/esm/icons/align-vertical-space-between.js
var AlignVerticalSpaceBetween = [
  ["rect", { width: "14", height: "6", x: "5", y: "15", rx: "2" }],
  ["rect", { width: "10", height: "6", x: "7", y: "3", rx: "2" }],
  ["path", { d: "M2 21h20" }],
  ["path", { d: "M2 3h20" }]
];

// node_modules/lucide/dist/esm/icons/ambulance.js
var Ambulance = [
  ["path", { d: "M10 10H6" }],
  ["path", { d: "M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" }],
  [
    "path",
    {
      d: "M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14"
    }
  ],
  ["path", { d: "M8 8v4" }],
  ["path", { d: "M9 18h6" }],
  ["circle", { cx: "17", cy: "18", r: "2" }],
  ["circle", { cx: "7", cy: "18", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/ampersand.js
var Ampersand = [
  [
    "path",
    {
      d: "M17.5 12c0 4.4-3.6 8-8 8A4.5 4.5 0 0 1 5 15.5c0-6 8-4 8-8.5a3 3 0 1 0-6 0c0 3 2.5 8.5 12 13"
    }
  ],
  ["path", { d: "M16 12h3" }]
];

// node_modules/lucide/dist/esm/icons/ampersands.js
var Ampersands = [
  [
    "path",
    { d: "M10 17c-5-3-7-7-7-9a2 2 0 0 1 4 0c0 2.5-5 2.5-5 6 0 1.7 1.3 3 3 3 2.8 0 5-2.2 5-5" }
  ],
  [
    "path",
    { d: "M22 17c-5-3-7-7-7-9a2 2 0 0 1 4 0c0 2.5-5 2.5-5 6 0 1.7 1.3 3 3 3 2.8 0 5-2.2 5-5" }
  ]
];

// node_modules/lucide/dist/esm/icons/amphora.js
var Amphora = [
  ["path", { d: "M10 2v5.632c0 .424-.272.795-.653.982A6 6 0 0 0 6 14c.006 4 3 7 5 8" }],
  ["path", { d: "M10 5H8a2 2 0 0 0 0 4h.68" }],
  ["path", { d: "M14 2v5.632c0 .424.272.795.652.982A6 6 0 0 1 18 14c0 4-3 7-5 8" }],
  ["path", { d: "M14 5h2a2 2 0 0 1 0 4h-.68" }],
  ["path", { d: "M18 22H6" }],
  ["path", { d: "M9 2h6" }]
];

// node_modules/lucide/dist/esm/icons/anchor.js
var Anchor = [
  ["path", { d: "M12 22V8" }],
  ["path", { d: "M5 12H2a10 10 0 0 0 20 0h-3" }],
  ["circle", { cx: "12", cy: "5", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/angry.js
var Angry = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M16 16s-1.5-2-4-2-4 2-4 2" }],
  ["path", { d: "M7.5 8 10 9" }],
  ["path", { d: "m14 9 2.5-1" }],
  ["path", { d: "M9 10h.01" }],
  ["path", { d: "M15 10h.01" }]
];

// node_modules/lucide/dist/esm/icons/antenna.js
var Antenna = [
  ["path", { d: "M2 12 7 2" }],
  ["path", { d: "m7 12 5-10" }],
  ["path", { d: "m12 12 5-10" }],
  ["path", { d: "m17 12 5-10" }],
  ["path", { d: "M4.5 7h15" }],
  ["path", { d: "M12 16v6" }]
];

// node_modules/lucide/dist/esm/icons/annoyed.js
var Annoyed = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M8 15h8" }],
  ["path", { d: "M8 9h2" }],
  ["path", { d: "M14 9h2" }]
];

// node_modules/lucide/dist/esm/icons/anvil.js
var Anvil = [
  ["path", { d: "M7 10H6a4 4 0 0 1-4-4 1 1 0 0 1 1-1h4" }],
  ["path", { d: "M7 5a1 1 0 0 1 1-1h13a1 1 0 0 1 1 1 7 7 0 0 1-7 7H8a1 1 0 0 1-1-1z" }],
  ["path", { d: "M9 12v5" }],
  ["path", { d: "M15 12v5" }],
  ["path", { d: "M5 20a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3 1 1 0 0 1-1 1H6a1 1 0 0 1-1-1" }]
];

// node_modules/lucide/dist/esm/icons/aperture.js
var Aperture = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m14.31 8 5.74 9.94" }],
  ["path", { d: "M9.69 8h11.48" }],
  ["path", { d: "m7.38 12 5.74-9.94" }],
  ["path", { d: "M9.69 16 3.95 6.06" }],
  ["path", { d: "M14.31 16H2.83" }],
  ["path", { d: "m16.62 12-5.74 9.94" }]
];

// node_modules/lucide/dist/esm/icons/app-window-mac.js
var AppWindowMac = [
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["path", { d: "M6 8h.01" }],
  ["path", { d: "M10 8h.01" }],
  ["path", { d: "M14 8h.01" }]
];

// node_modules/lucide/dist/esm/icons/app-window.js
var AppWindow = [
  ["rect", { x: "2", y: "4", width: "20", height: "16", rx: "2" }],
  ["path", { d: "M10 4v4" }],
  ["path", { d: "M2 8h20" }],
  ["path", { d: "M6 4v4" }]
];

// node_modules/lucide/dist/esm/icons/apple.js
var Apple = [
  [
    "path",
    {
      d: "M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"
    }
  ],
  ["path", { d: "M10 2c1 .5 2 2 2 5" }]
];

// node_modules/lucide/dist/esm/icons/archive-restore.js
var ArchiveRestore = [
  ["rect", { width: "20", height: "5", x: "2", y: "3", rx: "1" }],
  ["path", { d: "M4 8v11a2 2 0 0 0 2 2h2" }],
  ["path", { d: "M20 8v11a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "m9 15 3-3 3 3" }],
  ["path", { d: "M12 12v9" }]
];

// node_modules/lucide/dist/esm/icons/archive-x.js
var ArchiveX = [
  ["rect", { width: "20", height: "5", x: "2", y: "3", rx: "1" }],
  ["path", { d: "M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" }],
  ["path", { d: "m9.5 17 5-5" }],
  ["path", { d: "m9.5 12 5 5" }]
];

// node_modules/lucide/dist/esm/icons/archive.js
var Archive = [
  ["rect", { width: "20", height: "5", x: "2", y: "3", rx: "1" }],
  ["path", { d: "M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" }],
  ["path", { d: "M10 12h4" }]
];

// node_modules/lucide/dist/esm/icons/armchair.js
var Armchair = [
  ["path", { d: "M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3" }],
  [
    "path",
    {
      d: "M3 16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"
    }
  ],
  ["path", { d: "M5 18v2" }],
  ["path", { d: "M19 18v2" }]
];

// node_modules/lucide/dist/esm/icons/arrow-big-down-dash.js
var ArrowBigDownDash = [
  ["path", { d: "M15 5H9" }],
  ["path", { d: "M15 9v3h4l-7 7-7-7h4V9z" }]
];

// node_modules/lucide/dist/esm/icons/arrow-big-down.js
var ArrowBigDown = [["path", { d: "M15 6v6h4l-7 7-7-7h4V6h6z" }]];

// node_modules/lucide/dist/esm/icons/arrow-big-left-dash.js
var ArrowBigLeftDash = [
  ["path", { d: "M19 15V9" }],
  ["path", { d: "M15 15h-3v4l-7-7 7-7v4h3v6z" }]
];

// node_modules/lucide/dist/esm/icons/arrow-big-left.js
var ArrowBigLeft = [["path", { d: "M18 15h-6v4l-7-7 7-7v4h6v6z" }]];

// node_modules/lucide/dist/esm/icons/arrow-big-right-dash.js
var ArrowBigRightDash = [
  ["path", { d: "M5 9v6" }],
  ["path", { d: "M9 9h3V5l7 7-7 7v-4H9V9z" }]
];

// node_modules/lucide/dist/esm/icons/arrow-big-up-dash.js
var ArrowBigUpDash = [
  ["path", { d: "M9 19h6" }],
  ["path", { d: "M9 15v-3H5l7-7 7 7h-4v3H9z" }]
];

// node_modules/lucide/dist/esm/icons/arrow-big-right.js
var ArrowBigRight = [["path", { d: "M6 9h6V5l7 7-7 7v-4H6V9z" }]];

// node_modules/lucide/dist/esm/icons/arrow-big-up.js
var ArrowBigUp = [["path", { d: "M9 18v-6H5l7-7 7 7h-4v6H9z" }]];

// node_modules/lucide/dist/esm/icons/arrow-down-0-1.js
var ArrowDown01 = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 20V4" }],
  ["rect", { x: "15", y: "4", width: "4", height: "6", ry: "2" }],
  ["path", { d: "M17 20v-6h-2" }],
  ["path", { d: "M15 20h4" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-1-0.js
var ArrowDown10 = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 20V4" }],
  ["path", { d: "M17 10V4h-2" }],
  ["path", { d: "M15 10h4" }],
  ["rect", { x: "15", y: "14", width: "4", height: "6", ry: "2" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-a-z.js
var ArrowDownAZ = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 20V4" }],
  ["path", { d: "M20 8h-5" }],
  ["path", { d: "M15 10V6.5a2.5 2.5 0 0 1 5 0V10" }],
  ["path", { d: "M15 14h5l-5 6h5" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-from-line.js
var ArrowDownFromLine = [
  ["path", { d: "M19 3H5" }],
  ["path", { d: "M12 21V7" }],
  ["path", { d: "m6 15 6 6 6-6" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-left.js
var ArrowDownLeft = [
  ["path", { d: "M17 7 7 17" }],
  ["path", { d: "M17 17H7V7" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-right.js
var ArrowDownRight = [
  ["path", { d: "m7 7 10 10" }],
  ["path", { d: "M17 7v10H7" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-narrow-wide.js
var ArrowDownNarrowWide = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 20V4" }],
  ["path", { d: "M11 4h4" }],
  ["path", { d: "M11 8h7" }],
  ["path", { d: "M11 12h10" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-to-dot.js
var ArrowDownToDot = [
  ["path", { d: "M12 2v14" }],
  ["path", { d: "m19 9-7 7-7-7" }],
  ["circle", { cx: "12", cy: "21", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-to-line.js
var ArrowDownToLine = [
  ["path", { d: "M12 17V3" }],
  ["path", { d: "m6 11 6 6 6-6" }],
  ["path", { d: "M19 21H5" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-up.js
var ArrowDownUp = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 20V4" }],
  ["path", { d: "m21 8-4-4-4 4" }],
  ["path", { d: "M17 4v16" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-wide-narrow.js
var ArrowDownWideNarrow = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 20V4" }],
  ["path", { d: "M11 4h10" }],
  ["path", { d: "M11 8h7" }],
  ["path", { d: "M11 12h4" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down-z-a.js
var ArrowDownZA = [
  ["path", { d: "m3 16 4 4 4-4" }],
  ["path", { d: "M7 4v16" }],
  ["path", { d: "M15 4h5l-5 6h5" }],
  ["path", { d: "M15 20v-3.5a2.5 2.5 0 0 1 5 0V20" }],
  ["path", { d: "M20 18h-5" }]
];

// node_modules/lucide/dist/esm/icons/arrow-down.js
var ArrowDown = [
  ["path", { d: "M12 5v14" }],
  ["path", { d: "m19 12-7 7-7-7" }]
];

// node_modules/lucide/dist/esm/icons/arrow-left-from-line.js
var ArrowLeftFromLine = [
  ["path", { d: "m9 6-6 6 6 6" }],
  ["path", { d: "M3 12h14" }],
  ["path", { d: "M21 19V5" }]
];

// node_modules/lucide/dist/esm/icons/arrow-left-right.js
var ArrowLeftRight = [
  ["path", { d: "M8 3 4 7l4 4" }],
  ["path", { d: "M4 7h16" }],
  ["path", { d: "m16 21 4-4-4-4" }],
  ["path", { d: "M20 17H4" }]
];

// node_modules/lucide/dist/esm/icons/arrow-left-to-line.js
var ArrowLeftToLine = [
  ["path", { d: "M3 19V5" }],
  ["path", { d: "m13 6-6 6 6 6" }],
  ["path", { d: "M7 12h14" }]
];

// node_modules/lucide/dist/esm/icons/arrow-left.js
var ArrowLeft = [
  ["path", { d: "m12 19-7-7 7-7" }],
  ["path", { d: "M19 12H5" }]
];

// node_modules/lucide/dist/esm/icons/arrow-right-from-line.js
var ArrowRightFromLine = [
  ["path", { d: "M3 5v14" }],
  ["path", { d: "M21 12H7" }],
  ["path", { d: "m15 18 6-6-6-6" }]
];

// node_modules/lucide/dist/esm/icons/arrow-right-left.js
var ArrowRightLeft = [
  ["path", { d: "m16 3 4 4-4 4" }],
  ["path", { d: "M20 7H4" }],
  ["path", { d: "m8 21-4-4 4-4" }],
  ["path", { d: "M4 17h16" }]
];

// node_modules/lucide/dist/esm/icons/arrow-right-to-line.js
var ArrowRightToLine = [
  ["path", { d: "M17 12H3" }],
  ["path", { d: "m11 18 6-6-6-6" }],
  ["path", { d: "M21 5v14" }]
];

// node_modules/lucide/dist/esm/icons/arrow-right.js
var ArrowRight = [
  ["path", { d: "M5 12h14" }],
  ["path", { d: "m12 5 7 7-7 7" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-0-1.js
var ArrowUp01 = [
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }],
  ["rect", { x: "15", y: "4", width: "4", height: "6", ry: "2" }],
  ["path", { d: "M17 20v-6h-2" }],
  ["path", { d: "M15 20h4" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-1-0.js
var ArrowUp10 = [
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }],
  ["path", { d: "M17 10V4h-2" }],
  ["path", { d: "M15 10h4" }],
  ["rect", { x: "15", y: "14", width: "4", height: "6", ry: "2" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-a-z.js
var ArrowUpAZ = [
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }],
  ["path", { d: "M20 8h-5" }],
  ["path", { d: "M15 10V6.5a2.5 2.5 0 0 1 5 0V10" }],
  ["path", { d: "M15 14h5l-5 6h5" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-down.js
var ArrowUpDown = [
  ["path", { d: "m21 16-4 4-4-4" }],
  ["path", { d: "M17 20V4" }],
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-from-dot.js
var ArrowUpFromDot = [
  ["path", { d: "m5 9 7-7 7 7" }],
  ["path", { d: "M12 16V2" }],
  ["circle", { cx: "12", cy: "21", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-from-line.js
var ArrowUpFromLine = [
  ["path", { d: "m18 9-6-6-6 6" }],
  ["path", { d: "M12 3v14" }],
  ["path", { d: "M5 21h14" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-left.js
var ArrowUpLeft = [
  ["path", { d: "M7 17V7h10" }],
  ["path", { d: "M17 17 7 7" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-narrow-wide.js
var ArrowUpNarrowWide = [
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }],
  ["path", { d: "M11 12h4" }],
  ["path", { d: "M11 16h7" }],
  ["path", { d: "M11 20h10" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-right.js
var ArrowUpRight = [
  ["path", { d: "M7 7h10v10" }],
  ["path", { d: "M7 17 17 7" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-to-line.js
var ArrowUpToLine = [
  ["path", { d: "M5 3h14" }],
  ["path", { d: "m18 13-6-6-6 6" }],
  ["path", { d: "M12 7v14" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-wide-narrow.js
var ArrowUpWideNarrow = [
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }],
  ["path", { d: "M11 12h10" }],
  ["path", { d: "M11 16h7" }],
  ["path", { d: "M11 20h4" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up-z-a.js
var ArrowUpZA = [
  ["path", { d: "m3 8 4-4 4 4" }],
  ["path", { d: "M7 4v16" }],
  ["path", { d: "M15 4h5l-5 6h5" }],
  ["path", { d: "M15 20v-3.5a2.5 2.5 0 0 1 5 0V20" }],
  ["path", { d: "M20 18h-5" }]
];

// node_modules/lucide/dist/esm/icons/arrows-up-from-line.js
var ArrowsUpFromLine = [
  ["path", { d: "m4 6 3-3 3 3" }],
  ["path", { d: "M7 17V3" }],
  ["path", { d: "m14 6 3-3 3 3" }],
  ["path", { d: "M17 17V3" }],
  ["path", { d: "M4 21h16" }]
];

// node_modules/lucide/dist/esm/icons/arrow-up.js
var ArrowUp = [
  ["path", { d: "m5 12 7-7 7 7" }],
  ["path", { d: "M12 19V5" }]
];

// node_modules/lucide/dist/esm/icons/asterisk.js
var Asterisk = [
  ["path", { d: "M12 6v12" }],
  ["path", { d: "M17.196 9 6.804 15" }],
  ["path", { d: "m6.804 9 10.392 6" }]
];

// node_modules/lucide/dist/esm/icons/at-sign.js
var AtSign = [
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["path", { d: "M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8" }]
];

// node_modules/lucide/dist/esm/icons/atom.js
var Atom = [
  ["circle", { cx: "12", cy: "12", r: "1" }],
  [
    "path",
    {
      d: "M20.2 20.2c2.04-2.03.02-7.36-4.5-11.9-4.54-4.52-9.87-6.54-11.9-4.5-2.04 2.03-.02 7.36 4.5 11.9 4.54 4.52 9.87 6.54 11.9 4.5Z"
    }
  ],
  [
    "path",
    {
      d: "M15.7 15.7c4.52-4.54 6.54-9.87 4.5-11.9-2.03-2.04-7.36-.02-11.9 4.5-4.52 4.54-6.54 9.87-4.5 11.9 2.03 2.04 7.36.02 11.9-4.5Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/audio-lines.js
var AudioLines = [
  ["path", { d: "M2 10v3" }],
  ["path", { d: "M6 6v11" }],
  ["path", { d: "M10 3v18" }],
  ["path", { d: "M14 8v7" }],
  ["path", { d: "M18 5v13" }],
  ["path", { d: "M22 10v3" }]
];

// node_modules/lucide/dist/esm/icons/audio-waveform.js
var AudioWaveform = [
  [
    "path",
    {
      d: "M2 13a2 2 0 0 0 2-2V7a2 2 0 0 1 4 0v13a2 2 0 0 0 4 0V4a2 2 0 0 1 4 0v13a2 2 0 0 0 4 0v-4a2 2 0 0 1 2-2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/award.js
var Award = [
  [
    "path",
    {
      d: "m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"
    }
  ],
  ["circle", { cx: "12", cy: "8", r: "6" }]
];

// node_modules/lucide/dist/esm/icons/axe.js
var Axe = [
  ["path", { d: "m14 12-8.5 8.5a2.12 2.12 0 1 1-3-3L11 9" }],
  ["path", { d: "M15 13 9 7l4-4 6 6h3a8 8 0 0 1-7 7z" }]
];

// node_modules/lucide/dist/esm/icons/axis-3d.js
var Axis3d = [
  ["path", { d: "M4 4v16h16" }],
  ["path", { d: "m4 20 7-7" }]
];

// node_modules/lucide/dist/esm/icons/baby.js
var Baby = [
  ["path", { d: "M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5" }],
  ["path", { d: "M15 12h.01" }],
  [
    "path",
    {
      d: "M19.38 6.813A9 9 0 0 1 20.8 10.2a2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1"
    }
  ],
  ["path", { d: "M9 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/backpack.js
var Backpack = [
  ["path", { d: "M4 10a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" }],
  ["path", { d: "M8 10h8" }],
  ["path", { d: "M8 18h8" }],
  ["path", { d: "M8 22v-6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6" }],
  ["path", { d: "M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" }]
];

// node_modules/lucide/dist/esm/icons/badge-alert.js
var BadgeAlert = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["line", { x1: "12", x2: "12", y1: "8", y2: "12" }],
  ["line", { x1: "12", x2: "12.01", y1: "16", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/badge-cent.js
var BadgeCent = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M12 7v10" }],
  ["path", { d: "M15.4 10a4 4 0 1 0 0 4" }]
];

// node_modules/lucide/dist/esm/icons/badge-check.js
var BadgeCheck = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "m9 12 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/badge-dollar-sign.js
var BadgeDollarSign = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" }],
  ["path", { d: "M12 18V6" }]
];

// node_modules/lucide/dist/esm/icons/badge-euro.js
var BadgeEuro = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M7 12h5" }],
  ["path", { d: "M15 9.4a4 4 0 1 0 0 5.2" }]
];

// node_modules/lucide/dist/esm/icons/badge-help.js
var BadgeHelp = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" }],
  ["line", { x1: "12", x2: "12.01", y1: "17", y2: "17" }]
];

// node_modules/lucide/dist/esm/icons/badge-indian-rupee.js
var BadgeIndianRupee = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M8 8h8" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "m13 17-5-1h1a4 4 0 0 0 0-8" }]
];

// node_modules/lucide/dist/esm/icons/badge-info.js
var BadgeInfo = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["line", { x1: "12", x2: "12", y1: "16", y2: "12" }],
  ["line", { x1: "12", x2: "12.01", y1: "8", y2: "8" }]
];

// node_modules/lucide/dist/esm/icons/badge-japanese-yen.js
var BadgeJapaneseYen = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "m9 8 3 3v7" }],
  ["path", { d: "m12 11 3-3" }],
  ["path", { d: "M9 12h6" }],
  ["path", { d: "M9 16h6" }]
];

// node_modules/lucide/dist/esm/icons/badge-minus.js
var BadgeMinus = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["line", { x1: "8", x2: "16", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/badge-percent.js
var BadgePercent = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "M9 9h.01" }],
  ["path", { d: "M15 15h.01" }]
];

// node_modules/lucide/dist/esm/icons/badge-plus.js
var BadgePlus = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["line", { x1: "12", x2: "12", y1: "8", y2: "16" }],
  ["line", { x1: "8", x2: "16", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/badge-pound-sterling.js
var BadgePoundSterling = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M8 12h4" }],
  ["path", { d: "M10 16V9.5a2.5 2.5 0 0 1 5 0" }],
  ["path", { d: "M8 16h7" }]
];

// node_modules/lucide/dist/esm/icons/badge-russian-ruble.js
var BadgeRussianRuble = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M9 16h5" }],
  ["path", { d: "M9 12h5a2 2 0 1 0 0-4h-3v9" }]
];

// node_modules/lucide/dist/esm/icons/badge-x.js
var BadgeX = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["line", { x1: "15", x2: "9", y1: "9", y2: "15" }],
  ["line", { x1: "9", x2: "15", y1: "9", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/badge-swiss-franc.js
var BadgeSwissFranc = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ],
  ["path", { d: "M11 17V8h4" }],
  ["path", { d: "M11 12h3" }],
  ["path", { d: "M9 16h4" }]
];

// node_modules/lucide/dist/esm/icons/badge.js
var Badge = [
  [
    "path",
    {
      d: "M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/baggage-claim.js
var BaggageClaim = [
  ["path", { d: "M22 18H6a2 2 0 0 1-2-2V7a2 2 0 0 0-2-2" }],
  ["path", { d: "M17 14V4a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v10" }],
  ["rect", { width: "13", height: "8", x: "8", y: "6", rx: "1" }],
  ["circle", { cx: "18", cy: "20", r: "2" }],
  ["circle", { cx: "9", cy: "20", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/ban.js
var Ban = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m4.9 4.9 14.2 14.2" }]
];

// node_modules/lucide/dist/esm/icons/banana.js
var Banana = [
  ["path", { d: "M4 13c3.5-2 8-2 10 2a5.5 5.5 0 0 1 8 5" }],
  [
    "path",
    {
      d: "M5.15 17.89c5.52-1.52 8.65-6.89 7-12C11.55 4 11.5 2 13 2c3.22 0 5 5.5 5 8 0 6.5-4.2 12-10.49 12C5.11 22 2 22 2 20c0-1.5 1.14-1.55 3.15-2.11Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/bandage.js
var Bandage = [
  ["path", { d: "M10 10.01h.01" }],
  ["path", { d: "M10 14.01h.01" }],
  ["path", { d: "M14 10.01h.01" }],
  ["path", { d: "M14 14.01h.01" }],
  ["path", { d: "M18 6v11.5" }],
  ["path", { d: "M6 6v12" }],
  ["rect", { x: "2", y: "6", width: "20", height: "12", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/banknote-arrow-down.js
var BanknoteArrowDown = [
  ["path", { d: "M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" }],
  ["path", { d: "m16 19 3 3 3-3" }],
  ["path", { d: "M18 12h.01" }],
  ["path", { d: "M19 16v6" }],
  ["path", { d: "M6 12h.01" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/banknote-arrow-up.js
var BanknoteArrowUp = [
  ["path", { d: "M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" }],
  ["path", { d: "M18 12h.01" }],
  ["path", { d: "M19 22v-6" }],
  ["path", { d: "m22 19-3-3-3 3" }],
  ["path", { d: "M6 12h.01" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/banknote-x.js
var BanknoteX = [
  ["path", { d: "M13 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" }],
  ["path", { d: "m17 17 5 5" }],
  ["path", { d: "M18 12h.01" }],
  ["path", { d: "m22 17-5 5" }],
  ["path", { d: "M6 12h.01" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/banknote.js
var Banknote = [
  ["rect", { width: "20", height: "12", x: "2", y: "6", rx: "2" }],
  ["circle", { cx: "12", cy: "12", r: "2" }],
  ["path", { d: "M6 12h.01M18 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/barcode.js
var Barcode = [
  ["path", { d: "M3 5v14" }],
  ["path", { d: "M8 5v14" }],
  ["path", { d: "M12 5v14" }],
  ["path", { d: "M17 5v14" }],
  ["path", { d: "M21 5v14" }]
];

// node_modules/lucide/dist/esm/icons/baseline.js
var Baseline = [
  ["path", { d: "M4 20h16" }],
  ["path", { d: "m6 16 6-12 6 12" }],
  ["path", { d: "M8 12h8" }]
];

// node_modules/lucide/dist/esm/icons/bath.js
var Bath = [
  ["path", { d: "M10 4 8 6" }],
  ["path", { d: "M17 19v2" }],
  ["path", { d: "M2 12h20" }],
  ["path", { d: "M7 19v2" }],
  ["path", { d: "M9 5 7.621 3.621A2.121 2.121 0 0 0 4 5v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5" }]
];

// node_modules/lucide/dist/esm/icons/battery-charging.js
var BatteryCharging = [
  ["path", { d: "M15 7h1a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M6 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h1" }],
  ["path", { d: "m11 7-3 5h4l-3 5" }],
  ["line", { x1: "22", x2: "22", y1: "11", y2: "13" }]
];

// node_modules/lucide/dist/esm/icons/battery-low.js
var BatteryLow = [
  ["rect", { width: "16", height: "10", x: "2", y: "7", rx: "2", ry: "2" }],
  ["line", { x1: "22", x2: "22", y1: "11", y2: "13" }],
  ["line", { x1: "6", x2: "6", y1: "11", y2: "13" }]
];

// node_modules/lucide/dist/esm/icons/battery-full.js
var BatteryFull = [
  ["rect", { width: "16", height: "10", x: "2", y: "7", rx: "2", ry: "2" }],
  ["line", { x1: "22", x2: "22", y1: "11", y2: "13" }],
  ["line", { x1: "6", x2: "6", y1: "11", y2: "13" }],
  ["line", { x1: "10", x2: "10", y1: "11", y2: "13" }],
  ["line", { x1: "14", x2: "14", y1: "11", y2: "13" }]
];

// node_modules/lucide/dist/esm/icons/battery-medium.js
var BatteryMedium = [
  ["rect", { width: "16", height: "10", x: "2", y: "7", rx: "2", ry: "2" }],
  ["line", { x1: "22", x2: "22", y1: "11", y2: "13" }],
  ["line", { x1: "6", x2: "6", y1: "11", y2: "13" }],
  ["line", { x1: "10", x2: "10", y1: "11", y2: "13" }]
];

// node_modules/lucide/dist/esm/icons/battery-plus.js
var BatteryPlus = [
  ["path", { d: "M10 9v6" }],
  ["path", { d: "M13.5 7H16a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2.5" }],
  ["path", { d: "M22 11v2" }],
  ["path", { d: "M6.5 17H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2.5" }],
  ["path", { d: "M7 12h6" }]
];

// node_modules/lucide/dist/esm/icons/battery-warning.js
var BatteryWarning = [
  ["path", { d: "M10 17h.01" }],
  ["path", { d: "M10 7v6" }],
  ["path", { d: "M14 7h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M22 11v2" }],
  ["path", { d: "M6 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" }]
];

// node_modules/lucide/dist/esm/icons/battery.js
var Battery = [
  ["rect", { width: "16", height: "10", x: "2", y: "7", rx: "2", ry: "2" }],
  ["line", { x1: "22", x2: "22", y1: "11", y2: "13" }]
];

// node_modules/lucide/dist/esm/icons/beaker.js
var Beaker = [
  ["path", { d: "M4.5 3h15" }],
  ["path", { d: "M6 3v16a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V3" }],
  ["path", { d: "M6 14h12" }]
];

// node_modules/lucide/dist/esm/icons/bean-off.js
var BeanOff = [
  ["path", { d: "M9 9c-.64.64-1.521.954-2.402 1.165A6 6 0 0 0 8 22a13.96 13.96 0 0 0 9.9-4.1" }],
  ["path", { d: "M10.75 5.093A6 6 0 0 1 22 8c0 2.411-.61 4.68-1.683 6.66" }],
  ["path", { d: "M5.341 10.62a4 4 0 0 0 6.487 1.208M10.62 5.341a4.015 4.015 0 0 1 2.039 2.04" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/bean.js
var Bean = [
  [
    "path",
    {
      d: "M10.165 6.598C9.954 7.478 9.64 8.36 9 9c-.64.64-1.521.954-2.402 1.165A6 6 0 0 0 8 22c7.732 0 14-6.268 14-14a6 6 0 0 0-11.835-1.402Z"
    }
  ],
  ["path", { d: "M5.341 10.62a4 4 0 1 0 5.279-5.28" }]
];

// node_modules/lucide/dist/esm/icons/bed-double.js
var BedDouble = [
  ["path", { d: "M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8" }],
  ["path", { d: "M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4" }],
  ["path", { d: "M12 4v6" }],
  ["path", { d: "M2 18h20" }]
];

// node_modules/lucide/dist/esm/icons/bed-single.js
var BedSingle = [
  ["path", { d: "M3 20v-8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8" }],
  ["path", { d: "M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4" }],
  ["path", { d: "M3 18h18" }]
];

// node_modules/lucide/dist/esm/icons/bed.js
var Bed = [
  ["path", { d: "M2 4v16" }],
  ["path", { d: "M2 8h18a2 2 0 0 1 2 2v10" }],
  ["path", { d: "M2 17h20" }],
  ["path", { d: "M6 8v9" }]
];

// node_modules/lucide/dist/esm/icons/beef.js
var Beef = [
  [
    "path",
    {
      d: "M16.4 13.7A6.5 6.5 0 1 0 6.28 6.6c-1.1 3.13-.78 3.9-3.18 6.08A3 3 0 0 0 5 18c4 0 8.4-1.8 11.4-4.3"
    }
  ],
  [
    "path",
    {
      d: "m18.5 6 2.19 4.5a6.48 6.48 0 0 1-2.29 7.2C15.4 20.2 11 22 7 22a3 3 0 0 1-2.68-1.66L2.4 16.5"
    }
  ],
  ["circle", { cx: "12.5", cy: "8.5", r: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/beer-off.js
var BeerOff = [
  ["path", { d: "M13 13v5" }],
  ["path", { d: "M17 11.47V8" }],
  ["path", { d: "M17 11h1a3 3 0 0 1 2.745 4.211" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M5 8v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-3" }],
  ["path", { d: "M7.536 7.535C6.766 7.649 6.154 8 5.5 8a2.5 2.5 0 0 1-1.768-4.268" }],
  [
    "path",
    {
      d: "M8.727 3.204C9.306 2.767 9.885 2 11 2c1.56 0 2 1.5 3 1.5s1.72-.5 2.5-.5a1 1 0 1 1 0 5c-.78 0-1.5-.5-2.5-.5a3.149 3.149 0 0 0-.842.12"
    }
  ],
  ["path", { d: "M9 14.6V18" }]
];

// node_modules/lucide/dist/esm/icons/beer.js
var Beer = [
  ["path", { d: "M17 11h1a3 3 0 0 1 0 6h-1" }],
  ["path", { d: "M9 12v6" }],
  ["path", { d: "M13 12v6" }],
  [
    "path",
    {
      d: "M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.72.5-2.5.5a2.5 2.5 0 0 1 0-5c.78 0 1.57.5 2.5.5S9.44 2 11 2s2 1.5 3 1.5 1.72-.5 2.5-.5a2.5 2.5 0 0 1 0 5c-.78 0-1.5-.5-2.5-.5Z"
    }
  ],
  ["path", { d: "M5 8v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8" }]
];

// node_modules/lucide/dist/esm/icons/bell-dot.js
var BellDot = [
  ["path", { d: "M10.268 21a2 2 0 0 0 3.464 0" }],
  [
    "path",
    {
      d: "M13.916 2.314A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.74 7.327A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673 9 9 0 0 1-.585-.665"
    }
  ],
  ["circle", { cx: "18", cy: "8", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/bell-electric.js
var BellElectric = [
  ["path", { d: "M18.518 17.347A7 7 0 0 1 14 19" }],
  ["path", { d: "M18.8 4A11 11 0 0 1 20 9" }],
  ["path", { d: "M9 9h.01" }],
  ["circle", { cx: "20", cy: "16", r: "2" }],
  ["circle", { cx: "9", cy: "9", r: "7" }],
  ["rect", { x: "4", y: "16", width: "10", height: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/bell-minus.js
var BellMinus = [
  ["path", { d: "M10.268 21a2 2 0 0 0 3.464 0" }],
  ["path", { d: "M15 8h6" }],
  [
    "path",
    {
      d: "M16.243 3.757A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673A9.4 9.4 0 0 1 18.667 12"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/bell-plus.js
var BellPlus = [
  ["path", { d: "M10.268 21a2 2 0 0 0 3.464 0" }],
  ["path", { d: "M15 8h6" }],
  ["path", { d: "M18 5v6" }],
  [
    "path",
    {
      d: "M20.002 14.464a9 9 0 0 0 .738.863A1 1 0 0 1 20 17H4a1 1 0 0 1-.74-1.673C4.59 13.956 6 12.499 6 8a6 6 0 0 1 8.75-5.332"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/bell-off.js
var BellOff = [
  ["path", { d: "M10.268 21a2 2 0 0 0 3.464 0" }],
  ["path", { d: "M17 17H4a1 1 0 0 1-.74-1.673C4.59 13.956 6 12.499 6 8a6 6 0 0 1 .258-1.742" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M8.668 3.01A6 6 0 0 1 18 8c0 2.687.77 4.653 1.707 6.05" }]
];

// node_modules/lucide/dist/esm/icons/bell.js
var Bell = [
  ["path", { d: "M10.268 21a2 2 0 0 0 3.464 0" }],
  [
    "path",
    {
      d: "M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/bell-ring.js
var BellRing = [
  ["path", { d: "M10.268 21a2 2 0 0 0 3.464 0" }],
  ["path", { d: "M22 8c0-2.3-.8-4.3-2-6" }],
  [
    "path",
    {
      d: "M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"
    }
  ],
  ["path", { d: "M4 2C2.8 3.7 2 5.7 2 8" }]
];

// node_modules/lucide/dist/esm/icons/between-horizontal-end.js
var BetweenHorizontalEnd = [
  ["rect", { width: "13", height: "7", x: "3", y: "3", rx: "1" }],
  ["path", { d: "m22 15-3-3 3-3" }],
  ["rect", { width: "13", height: "7", x: "3", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/between-horizontal-start.js
var BetweenHorizontalStart = [
  ["rect", { width: "13", height: "7", x: "8", y: "3", rx: "1" }],
  ["path", { d: "m2 9 3 3-3 3" }],
  ["rect", { width: "13", height: "7", x: "8", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/between-vertical-end.js
var BetweenVerticalEnd = [
  ["rect", { width: "7", height: "13", x: "3", y: "3", rx: "1" }],
  ["path", { d: "m9 22 3-3 3 3" }],
  ["rect", { width: "7", height: "13", x: "14", y: "3", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/between-vertical-start.js
var BetweenVerticalStart = [
  ["rect", { width: "7", height: "13", x: "3", y: "8", rx: "1" }],
  ["path", { d: "m15 2-3 3-3-3" }],
  ["rect", { width: "7", height: "13", x: "14", y: "8", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/biceps-flexed.js
var BicepsFlexed = [
  [
    "path",
    {
      d: "M12.409 13.017A5 5 0 0 1 22 15c0 3.866-4 7-9 7-4.077 0-8.153-.82-10.371-2.462-.426-.316-.631-.832-.62-1.362C2.118 12.723 2.627 2 10 2a3 3 0 0 1 3 3 2 2 0 0 1-2 2c-1.105 0-1.64-.444-2-1"
    }
  ],
  ["path", { d: "M15 14a5 5 0 0 0-7.584 2" }],
  ["path", { d: "M9.964 6.825C8.019 7.977 9.5 13 8 15" }]
];

// node_modules/lucide/dist/esm/icons/bike.js
var Bike = [
  ["circle", { cx: "18.5", cy: "17.5", r: "3.5" }],
  ["circle", { cx: "5.5", cy: "17.5", r: "3.5" }],
  ["circle", { cx: "15", cy: "5", r: "1" }],
  ["path", { d: "M12 17.5V14l-3-3 4-3 2 3h2" }]
];

// node_modules/lucide/dist/esm/icons/binoculars.js
var Binoculars = [
  ["path", { d: "M10 10h4" }],
  ["path", { d: "M19 7V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v3" }],
  [
    "path",
    {
      d: "M20 21a2 2 0 0 0 2-2v-3.851c0-1.39-2-2.962-2-4.829V8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v11a2 2 0 0 0 2 2z"
    }
  ],
  ["path", { d: "M 22 16 L 2 16" }],
  [
    "path",
    {
      d: "M4 21a2 2 0 0 1-2-2v-3.851c0-1.39 2-2.962 2-4.829V8a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v11a2 2 0 0 1-2 2z"
    }
  ],
  ["path", { d: "M9 7V4a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v3" }]
];

// node_modules/lucide/dist/esm/icons/binary.js
var Binary = [
  ["rect", { x: "14", y: "14", width: "4", height: "6", rx: "2" }],
  ["rect", { x: "6", y: "4", width: "4", height: "6", rx: "2" }],
  ["path", { d: "M6 20h4" }],
  ["path", { d: "M14 10h4" }],
  ["path", { d: "M6 14h2v6" }],
  ["path", { d: "M14 4h2v6" }]
];

// node_modules/lucide/dist/esm/icons/biohazard.js
var Biohazard = [
  ["circle", { cx: "12", cy: "11.9", r: "2" }],
  ["path", { d: "M6.7 3.4c-.9 2.5 0 5.2 2.2 6.7C6.5 9 3.7 9.6 2 11.6" }],
  ["path", { d: "m8.9 10.1 1.4.8" }],
  ["path", { d: "M17.3 3.4c.9 2.5 0 5.2-2.2 6.7 2.4-1.2 5.2-.6 6.9 1.5" }],
  ["path", { d: "m15.1 10.1-1.4.8" }],
  ["path", { d: "M16.7 20.8c-2.6-.4-4.6-2.6-4.7-5.3-.2 2.6-2.1 4.8-4.7 5.2" }],
  ["path", { d: "M12 13.9v1.6" }],
  ["path", { d: "M13.5 5.4c-1-.2-2-.2-3 0" }],
  ["path", { d: "M17 16.4c.7-.7 1.2-1.6 1.5-2.5" }],
  ["path", { d: "M5.5 13.9c.3.9.8 1.8 1.5 2.5" }]
];

// node_modules/lucide/dist/esm/icons/bird.js
var Bird = [
  ["path", { d: "M16 7h.01" }],
  ["path", { d: "M3.4 18H12a8 8 0 0 0 8-8V7a4 4 0 0 0-7.28-2.3L2 20" }],
  ["path", { d: "m20 7 2 .5-2 .5" }],
  ["path", { d: "M10 18v3" }],
  ["path", { d: "M14 17.75V21" }],
  ["path", { d: "M7 18a6 6 0 0 0 3.84-10.61" }]
];

// node_modules/lucide/dist/esm/icons/bitcoin.js
var Bitcoin = [
  [
    "path",
    {
      d: "M11.767 19.089c4.924.868 6.14-6.025 1.216-6.894m-1.216 6.894L5.86 18.047m5.908 1.042-.347 1.97m1.563-8.864c4.924.869 6.14-6.025 1.215-6.893m-1.215 6.893-3.94-.694m5.155-6.2L8.29 4.26m5.908 1.042.348-1.97M7.48 20.364l3.126-17.727"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/blend.js
var Blend = [
  ["circle", { cx: "9", cy: "9", r: "7" }],
  ["circle", { cx: "15", cy: "15", r: "7" }]
];

// node_modules/lucide/dist/esm/icons/blinds.js
var Blinds = [
  ["path", { d: "M3 3h18" }],
  ["path", { d: "M20 7H8" }],
  ["path", { d: "M20 11H8" }],
  ["path", { d: "M10 19h10" }],
  ["path", { d: "M8 15h12" }],
  ["path", { d: "M4 3v14" }],
  ["circle", { cx: "4", cy: "19", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/blocks.js
var Blocks = [
  ["rect", { width: "7", height: "7", x: "14", y: "3", rx: "1" }],
  [
    "path",
    {
      d: "M10 21V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H3"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/bluetooth-connected.js
var BluetoothConnected = [
  ["path", { d: "m7 7 10 10-5 5V2l5 5L7 17" }],
  ["line", { x1: "18", x2: "21", y1: "12", y2: "12" }],
  ["line", { x1: "3", x2: "6", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/bluetooth-off.js
var BluetoothOff = [
  ["path", { d: "m17 17-5 5V12l-5 5" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M14.5 9.5 17 7l-5-5v4.5" }]
];

// node_modules/lucide/dist/esm/icons/bluetooth-searching.js
var BluetoothSearching = [
  ["path", { d: "m7 7 10 10-5 5V2l5 5L7 17" }],
  ["path", { d: "M20.83 14.83a4 4 0 0 0 0-5.66" }],
  ["path", { d: "M18 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/bold.js
var Bold = [
  ["path", { d: "M6 12h9a4 4 0 0 1 0 8H7a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h7a4 4 0 0 1 0 8" }]
];

// node_modules/lucide/dist/esm/icons/bolt.js
var Bolt = [
  [
    "path",
    {
      d: "M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
    }
  ],
  ["circle", { cx: "12", cy: "12", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/bluetooth.js
var Bluetooth = [["path", { d: "m7 7 10 10-5 5V2l5 5L7 17" }]];

// node_modules/lucide/dist/esm/icons/bone.js
var Bone = [
  [
    "path",
    {
      d: "M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/bomb.js
var Bomb = [
  ["circle", { cx: "11", cy: "13", r: "9" }],
  [
    "path",
    { d: "M14.35 4.65 16.3 2.7a2.41 2.41 0 0 1 3.4 0l1.6 1.6a2.4 2.4 0 0 1 0 3.4l-1.95 1.95" }
  ],
  ["path", { d: "m22 2-1.5 1.5" }]
];

// node_modules/lucide/dist/esm/icons/book-audio.js
var BookAudio = [
  ["path", { d: "M12 6v7" }],
  ["path", { d: "M16 8v3" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "M8 8v3" }]
];

// node_modules/lucide/dist/esm/icons/book-check.js
var BookCheck = [
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "m9 9.5 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/book-a.js
var BookA = [
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "m8 13 4-7 4 7" }],
  ["path", { d: "M9.1 11h5.7" }]
];

// node_modules/lucide/dist/esm/icons/book-copy.js
var BookCopy = [
  ["path", { d: "M2 16V4a2 2 0 0 1 2-2h11" }],
  [
    "path",
    { d: "M22 18H11a2 2 0 1 0 0 4h10.5a.5.5 0 0 0 .5-.5v-15a.5.5 0 0 0-.5-.5H11a2 2 0 0 0-2 2v12" }
  ],
  ["path", { d: "M5 14H4a2 2 0 1 0 0 4h1" }]
];

// node_modules/lucide/dist/esm/icons/book-down.js
var BookDown = [
  ["path", { d: "M12 13V7" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "m9 10 3 3 3-3" }]
];

// node_modules/lucide/dist/esm/icons/book-dashed.js
var BookDashed = [
  ["path", { d: "M12 17h1.5" }],
  ["path", { d: "M12 22h1.5" }],
  ["path", { d: "M12 2h1.5" }],
  ["path", { d: "M17.5 22H19a1 1 0 0 0 1-1" }],
  ["path", { d: "M17.5 2H19a1 1 0 0 1 1 1v1.5" }],
  ["path", { d: "M20 14v3h-2.5" }],
  ["path", { d: "M20 8.5V10" }],
  ["path", { d: "M4 10V8.5" }],
  ["path", { d: "M4 19.5V14" }],
  ["path", { d: "M4 4.5A2.5 2.5 0 0 1 6.5 2H8" }],
  ["path", { d: "M8 22H6.5a1 1 0 0 1 0-5H8" }]
];

// node_modules/lucide/dist/esm/icons/book-headphones.js
var BookHeadphones = [
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "M8 12v-2a4 4 0 0 1 8 0v2" }],
  ["circle", { cx: "15", cy: "12", r: "1" }],
  ["circle", { cx: "9", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/book-heart.js
var BookHeart = [
  [
    "path",
    {
      d: "M16 8.2A2.22 2.22 0 0 0 13.8 6c-.8 0-1.4.3-1.8.9-.4-.6-1-.9-1.8-.9A2.22 2.22 0 0 0 8 8.2c0 .6.3 1.2.7 1.6A226.652 226.652 0 0 0 12 13a404 404 0 0 0 3.3-3.1 2.413 2.413 0 0 0 .7-1.7"
    }
  ],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ]
];

// node_modules/lucide/dist/esm/icons/book-image.js
var BookImage = [
  ["path", { d: "m20 13.7-2.1-2.1a2 2 0 0 0-2.8 0L9.7 17" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["circle", { cx: "10", cy: "8", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/book-key.js
var BookKey = [
  ["path", { d: "m19 3 1 1" }],
  ["path", { d: "m20 2-4.5 4.5" }],
  ["path", { d: "M20 7.898V21a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }],
  ["path", { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2h7.844" }],
  ["circle", { cx: "14", cy: "8", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/book-marked.js
var BookMarked = [
  ["path", { d: "M10 2v8l3-3 3 3V2" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ]
];

// node_modules/lucide/dist/esm/icons/book-lock.js
var BookLock = [
  ["path", { d: "M18 6V4a2 2 0 1 0-4 0v2" }],
  ["path", { d: "M20 15v6a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }],
  ["path", { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H10" }],
  ["rect", { x: "12", y: "6", width: "8", height: "5", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/book-minus.js
var BookMinus = [
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "M9 10h6" }]
];

// node_modules/lucide/dist/esm/icons/book-open-check.js
var BookOpenCheck = [
  ["path", { d: "M12 21V7" }],
  ["path", { d: "m16 12 2 2 4-4" }],
  [
    "path",
    {
      d: "M22 6V4a1 1 0 0 0-1-1h-5a4 4 0 0 0-4 4 4 4 0 0 0-4-4H3a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h6a3 3 0 0 1 3 3 3 3 0 0 1 3-3h6a1 1 0 0 0 1-1v-1.3"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/book-open-text.js
var BookOpenText = [
  ["path", { d: "M12 7v14" }],
  ["path", { d: "M16 12h2" }],
  ["path", { d: "M16 8h2" }],
  [
    "path",
    {
      d: "M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"
    }
  ],
  ["path", { d: "M6 12h2" }],
  ["path", { d: "M6 8h2" }]
];

// node_modules/lucide/dist/esm/icons/book-open.js
var BookOpen = [
  ["path", { d: "M12 7v14" }],
  [
    "path",
    {
      d: "M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/book-plus.js
var BookPlus = [
  ["path", { d: "M12 7v6" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "M9 10h6" }]
];

// node_modules/lucide/dist/esm/icons/book-text.js
var BookText = [
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "M8 11h8" }],
  ["path", { d: "M8 7h6" }]
];

// node_modules/lucide/dist/esm/icons/book-type.js
var BookType = [
  ["path", { d: "M10 13h4" }],
  ["path", { d: "M12 6v7" }],
  ["path", { d: "M16 8V6H8v2" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ]
];

// node_modules/lucide/dist/esm/icons/book-up-2.js
var BookUp2 = [
  ["path", { d: "M12 13V7" }],
  ["path", { d: "M18 2h1a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }],
  ["path", { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2" }],
  ["path", { d: "m9 10 3-3 3 3" }],
  ["path", { d: "m9 5 3-3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/book-up.js
var BookUp = [
  ["path", { d: "M12 13V7" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "m9 10 3-3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/book-user.js
var BookUser = [
  ["path", { d: "M15 13a3 3 0 1 0-6 0" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["circle", { cx: "12", cy: "8", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/book-x.js
var BookX = [
  ["path", { d: "m14.5 7-5 5" }],
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ],
  ["path", { d: "m9.5 7 5 5" }]
];

// node_modules/lucide/dist/esm/icons/book.js
var Book = [
  [
    "path",
    { d: "M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" }
  ]
];

// node_modules/lucide/dist/esm/icons/bookmark-check.js
var BookmarkCheck = [
  ["path", { d: "m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z" }],
  ["path", { d: "m9 10 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/bookmark-minus.js
var BookmarkMinus = [
  ["path", { d: "m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" }],
  ["line", { x1: "15", x2: "9", y1: "10", y2: "10" }]
];

// node_modules/lucide/dist/esm/icons/bookmark-plus.js
var BookmarkPlus = [
  ["path", { d: "m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" }],
  ["line", { x1: "12", x2: "12", y1: "7", y2: "13" }],
  ["line", { x1: "15", x2: "9", y1: "10", y2: "10" }]
];

// node_modules/lucide/dist/esm/icons/bookmark-x.js
var BookmarkX = [
  ["path", { d: "m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z" }],
  ["path", { d: "m14.5 7.5-5 5" }],
  ["path", { d: "m9.5 7.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/bookmark.js
var Bookmark = [["path", { d: "m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" }]];

// node_modules/lucide/dist/esm/icons/boom-box.js
var BoomBox = [
  ["path", { d: "M4 9V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4" }],
  ["path", { d: "M8 8v1" }],
  ["path", { d: "M12 8v1" }],
  ["path", { d: "M16 8v1" }],
  ["rect", { width: "20", height: "12", x: "2", y: "9", rx: "2" }],
  ["circle", { cx: "8", cy: "15", r: "2" }],
  ["circle", { cx: "16", cy: "15", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/bot-message-square.js
var BotMessageSquare = [
  ["path", { d: "M12 6V2H8" }],
  ["path", { d: "m8 18-4 4V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2Z" }],
  ["path", { d: "M2 12h2" }],
  ["path", { d: "M9 11v2" }],
  ["path", { d: "M15 11v2" }],
  ["path", { d: "M20 12h2" }]
];

// node_modules/lucide/dist/esm/icons/bot-off.js
var BotOff = [
  ["path", { d: "M13.67 8H18a2 2 0 0 1 2 2v4.33" }],
  ["path", { d: "M2 14h2" }],
  ["path", { d: "M20 14h2" }],
  ["path", { d: "M22 22 2 2" }],
  ["path", { d: "M8 8H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 1.414-.586" }],
  ["path", { d: "M9 13v2" }],
  ["path", { d: "M9.67 4H12v2.33" }]
];

// node_modules/lucide/dist/esm/icons/bot.js
var Bot = [
  ["path", { d: "M12 8V4H8" }],
  ["rect", { width: "16", height: "12", x: "4", y: "8", rx: "2" }],
  ["path", { d: "M2 14h2" }],
  ["path", { d: "M20 14h2" }],
  ["path", { d: "M15 13v2" }],
  ["path", { d: "M9 13v2" }]
];

// node_modules/lucide/dist/esm/icons/bow-arrow.js
var BowArrow = [
  ["path", { d: "M17 3h4v4" }],
  ["path", { d: "M18.575 11.082a13 13 0 0 1 1.048 9.027 1.17 1.17 0 0 1-1.914.597L14 17" }],
  ["path", { d: "M7 10 3.29 6.29a1.17 1.17 0 0 1 .6-1.91 13 13 0 0 1 9.03 1.05" }],
  [
    "path",
    {
      d: "M7 14a1.7 1.7 0 0 0-1.207.5l-2.646 2.646A.5.5 0 0 0 3.5 18H5a1 1 0 0 1 1 1v1.5a.5.5 0 0 0 .854.354L9.5 18.207A1.7 1.7 0 0 0 10 17v-2a1 1 0 0 0-1-1z"
    }
  ],
  ["path", { d: "M9.707 14.293 21 3" }]
];

// node_modules/lucide/dist/esm/icons/box.js
var Box = [
  [
    "path",
    {
      d: "M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"
    }
  ],
  ["path", { d: "m3.3 7 8.7 5 8.7-5" }],
  ["path", { d: "M12 22V12" }]
];

// node_modules/lucide/dist/esm/icons/boxes.js
var Boxes = [
  [
    "path",
    {
      d: "M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z"
    }
  ],
  ["path", { d: "m7 16.5-4.74-2.85" }],
  ["path", { d: "m7 16.5 5-3" }],
  ["path", { d: "M7 16.5v5.17" }],
  [
    "path",
    {
      d: "M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z"
    }
  ],
  ["path", { d: "m17 16.5-5-3" }],
  ["path", { d: "m17 16.5 4.74-2.85" }],
  ["path", { d: "M17 16.5v5.17" }],
  [
    "path",
    {
      d: "M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z"
    }
  ],
  ["path", { d: "M12 8 7.26 5.15" }],
  ["path", { d: "m12 8 4.74-2.85" }],
  ["path", { d: "M12 13.5V8" }]
];

// node_modules/lucide/dist/esm/icons/braces.js
var Braces = [
  ["path", { d: "M8 3H7a2 2 0 0 0-2 2v5a2 2 0 0 1-2 2 2 2 0 0 1 2 2v5c0 1.1.9 2 2 2h1" }],
  ["path", { d: "M16 21h1a2 2 0 0 0 2-2v-5c0-1.1.9-2 2-2a2 2 0 0 1-2-2V5a2 2 0 0 0-2-2h-1" }]
];

// node_modules/lucide/dist/esm/icons/brackets.js
var Brackets = [
  ["path", { d: "M16 3h2a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-2" }],
  ["path", { d: "M8 21H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2" }]
];

// node_modules/lucide/dist/esm/icons/brain-cog.js
var BrainCog = [
  ["path", { d: "m10.852 14.772-.383.923" }],
  ["path", { d: "m10.852 9.228-.383-.923" }],
  ["path", { d: "m13.148 14.772.382.924" }],
  ["path", { d: "m13.531 8.305-.383.923" }],
  ["path", { d: "m14.772 10.852.923-.383" }],
  ["path", { d: "m14.772 13.148.923.383" }],
  [
    "path",
    {
      d: "M17.598 6.5A3 3 0 1 0 12 5a3 3 0 0 0-5.63-1.446 3 3 0 0 0-.368 1.571 4 4 0 0 0-2.525 5.771"
    }
  ],
  ["path", { d: "M17.998 5.125a4 4 0 0 1 2.525 5.771" }],
  ["path", { d: "M19.505 10.294a4 4 0 0 1-1.5 7.706" }],
  [
    "path",
    { d: "M4.032 17.483A4 4 0 0 0 11.464 20c.18-.311.892-.311 1.072 0a4 4 0 0 0 7.432-2.516" }
  ],
  ["path", { d: "M4.5 10.291A4 4 0 0 0 6 18" }],
  ["path", { d: "M6.002 5.125a3 3 0 0 0 .4 1.375" }],
  ["path", { d: "m9.228 10.852-.923-.383" }],
  ["path", { d: "m9.228 13.148-.923.383" }],
  ["circle", { cx: "12", cy: "12", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/brain-circuit.js
var BrainCircuit = [
  [
    "path",
    { d: "M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z" }
  ],
  ["path", { d: "M9 13a4.5 4.5 0 0 0 3-4" }],
  ["path", { d: "M6.003 5.125A3 3 0 0 0 6.401 6.5" }],
  ["path", { d: "M3.477 10.896a4 4 0 0 1 .585-.396" }],
  ["path", { d: "M6 18a4 4 0 0 1-1.967-.516" }],
  ["path", { d: "M12 13h4" }],
  ["path", { d: "M12 18h6a2 2 0 0 1 2 2v1" }],
  ["path", { d: "M12 8h8" }],
  ["path", { d: "M16 8V5a2 2 0 0 1 2-2" }],
  ["circle", { cx: "16", cy: "13", r: ".5" }],
  ["circle", { cx: "18", cy: "3", r: ".5" }],
  ["circle", { cx: "20", cy: "21", r: ".5" }],
  ["circle", { cx: "20", cy: "8", r: ".5" }]
];

// node_modules/lucide/dist/esm/icons/brain.js
var Brain = [
  [
    "path",
    { d: "M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z" }
  ],
  [
    "path",
    { d: "M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z" }
  ],
  ["path", { d: "M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4" }],
  ["path", { d: "M17.599 6.5a3 3 0 0 0 .399-1.375" }],
  ["path", { d: "M6.003 5.125A3 3 0 0 0 6.401 6.5" }],
  ["path", { d: "M3.477 10.896a4 4 0 0 1 .585-.396" }],
  ["path", { d: "M19.938 10.5a4 4 0 0 1 .585.396" }],
  ["path", { d: "M6 18a4 4 0 0 1-1.967-.516" }],
  ["path", { d: "M19.967 17.484A4 4 0 0 1 18 18" }]
];

// node_modules/lucide/dist/esm/icons/brick-wall-fire.js
var BrickWallFire = [
  ["path", { d: "M16 3v2.107" }],
  [
    "path",
    {
      d: "M17 9c1 3 2.5 3.5 3.5 4.5A5 5 0 0 1 22 17a5 5 0 0 1-10 0c0-.3 0-.6.1-.9a2 2 0 1 0 3.3-2C13 11.5 16 9 17 9"
    }
  ],
  ["path", { d: "M21 8.274V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.938" }],
  ["path", { d: "M3 15h5.253" }],
  ["path", { d: "M3 9h8.228" }],
  ["path", { d: "M8 15v6" }],
  ["path", { d: "M8 3v6" }]
];

// node_modules/lucide/dist/esm/icons/brick-wall.js
var BrickWall = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M12 9v6" }],
  ["path", { d: "M16 15v6" }],
  ["path", { d: "M16 3v6" }],
  ["path", { d: "M3 15h18" }],
  ["path", { d: "M3 9h18" }],
  ["path", { d: "M8 15v6" }],
  ["path", { d: "M8 3v6" }]
];

// node_modules/lucide/dist/esm/icons/briefcase-business.js
var BriefcaseBusiness = [
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" }],
  ["path", { d: "M22 13a18.15 18.15 0 0 1-20 0" }],
  ["rect", { width: "20", height: "14", x: "2", y: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/briefcase-conveyor-belt.js
var BriefcaseConveyorBelt = [
  ["path", { d: "M10 20v2" }],
  ["path", { d: "M14 20v2" }],
  ["path", { d: "M18 20v2" }],
  ["path", { d: "M21 20H3" }],
  ["path", { d: "M6 20v2" }],
  ["path", { d: "M8 16V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v12" }],
  ["rect", { x: "4", y: "6", width: "16", height: "10", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/briefcase-medical.js
var BriefcaseMedical = [
  ["path", { d: "M12 11v4" }],
  ["path", { d: "M14 13h-4" }],
  ["path", { d: "M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" }],
  ["path", { d: "M18 6v14" }],
  ["path", { d: "M6 6v14" }],
  ["rect", { width: "20", height: "14", x: "2", y: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/bring-to-front.js
var BringToFront = [
  ["rect", { x: "8", y: "8", width: "8", height: "8", rx: "2" }],
  ["path", { d: "M4 10a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2" }],
  ["path", { d: "M14 20a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2" }]
];

// node_modules/lucide/dist/esm/icons/brush-cleaning.js
var BrushCleaning = [
  ["path", { d: "m16 22-1-4" }],
  [
    "path",
    {
      d: "M19 13.99a1 1 0 0 0 1-1V12a2 2 0 0 0-2-2h-3a1 1 0 0 1-1-1V4a2 2 0 0 0-4 0v5a1 1 0 0 1-1 1H6a2 2 0 0 0-2 2v.99a1 1 0 0 0 1 1"
    }
  ],
  ["path", { d: "M5 14h14l1.973 6.767A1 1 0 0 1 20 22H4a1 1 0 0 1-.973-1.233z" }],
  ["path", { d: "m8 22 1-4" }]
];

// node_modules/lucide/dist/esm/icons/briefcase.js
var Briefcase = [
  ["path", { d: "M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" }],
  ["rect", { width: "20", height: "14", x: "2", y: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/brush.js
var Brush = [
  ["path", { d: "m11 10 3 3" }],
  ["path", { d: "M6.5 21A3.5 3.5 0 1 0 3 17.5a2.62 2.62 0 0 1-.708 1.792A1 1 0 0 0 3 21z" }],
  ["path", { d: "M9.969 17.031 21.378 5.624a1 1 0 0 0-3.002-3.002L6.967 14.031" }]
];

// node_modules/lucide/dist/esm/icons/bug-off.js
var BugOff = [
  ["path", { d: "M15 7.13V6a3 3 0 0 0-5.14-2.1L8 2" }],
  ["path", { d: "M14.12 3.88 16 2" }],
  ["path", { d: "M22 13h-4v-2a4 4 0 0 0-4-4h-1.3" }],
  ["path", { d: "M20.97 5c0 2.1-1.6 3.8-3.5 4" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M7.7 7.7A4 4 0 0 0 6 11v3a6 6 0 0 0 11.13 3.13" }],
  ["path", { d: "M12 20v-8" }],
  ["path", { d: "M6 13H2" }],
  ["path", { d: "M3 21c0-2.1 1.7-3.9 3.8-4" }]
];

// node_modules/lucide/dist/esm/icons/bug-play.js
var BugPlay = [
  [
    "path",
    {
      d: "M12.765 21.522a.5.5 0 0 1-.765-.424v-8.196a.5.5 0 0 1 .765-.424l5.878 3.674a1 1 0 0 1 0 1.696z"
    }
  ],
  ["path", { d: "M14.12 3.88 16 2" }],
  ["path", { d: "M18 11a4 4 0 0 0-4-4h-4a4 4 0 0 0-4 4v3a6.1 6.1 0 0 0 2 4.5" }],
  ["path", { d: "M20.97 5c0 2.1-1.6 3.8-3.5 4" }],
  ["path", { d: "M3 21c0-2.1 1.7-3.9 3.8-4" }],
  ["path", { d: "M6 13H2" }],
  ["path", { d: "M6.53 9C4.6 8.8 3 7.1 3 5" }],
  ["path", { d: "m8 2 1.88 1.88" }],
  ["path", { d: "M9 7.13v-1a3.003 3.003 0 1 1 6 0v1" }]
];

// node_modules/lucide/dist/esm/icons/bubbles.js
var Bubbles = [
  ["path", { d: "M7.2 14.8a2 2 0 0 1 2 2" }],
  ["circle", { cx: "18.5", cy: "8.5", r: "3.5" }],
  ["circle", { cx: "7.5", cy: "16.5", r: "5.5" }],
  ["circle", { cx: "7.5", cy: "4.5", r: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/bug.js
var Bug = [
  ["path", { d: "m8 2 1.88 1.88" }],
  ["path", { d: "M14.12 3.88 16 2" }],
  ["path", { d: "M9 7.13v-1a3.003 3.003 0 1 1 6 0v1" }],
  ["path", { d: "M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6" }],
  ["path", { d: "M12 20v-9" }],
  ["path", { d: "M6.53 9C4.6 8.8 3 7.1 3 5" }],
  ["path", { d: "M6 13H2" }],
  ["path", { d: "M3 21c0-2.1 1.7-3.9 3.8-4" }],
  ["path", { d: "M20.97 5c0 2.1-1.6 3.8-3.5 4" }],
  ["path", { d: "M22 13h-4" }],
  ["path", { d: "M17.2 17c2.1.1 3.8 1.9 3.8 4" }]
];

// node_modules/lucide/dist/esm/icons/building-2.js
var Building2 = [
  ["path", { d: "M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" }],
  ["path", { d: "M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" }],
  ["path", { d: "M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M10 6h4" }],
  ["path", { d: "M10 10h4" }],
  ["path", { d: "M10 14h4" }],
  ["path", { d: "M10 18h4" }]
];

// node_modules/lucide/dist/esm/icons/building.js
var Building = [
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2", ry: "2" }],
  ["path", { d: "M9 22v-4h6v4" }],
  ["path", { d: "M8 6h.01" }],
  ["path", { d: "M16 6h.01" }],
  ["path", { d: "M12 6h.01" }],
  ["path", { d: "M12 10h.01" }],
  ["path", { d: "M12 14h.01" }],
  ["path", { d: "M16 10h.01" }],
  ["path", { d: "M16 14h.01" }],
  ["path", { d: "M8 10h.01" }],
  ["path", { d: "M8 14h.01" }]
];

// node_modules/lucide/dist/esm/icons/bus.js
var Bus = [
  ["path", { d: "M8 6v6" }],
  ["path", { d: "M15 6v6" }],
  ["path", { d: "M2 12h19.6" }],
  [
    "path",
    {
      d: "M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"
    }
  ],
  ["circle", { cx: "7", cy: "18", r: "2" }],
  ["path", { d: "M9 18h5" }],
  ["circle", { cx: "16", cy: "18", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/cable-car.js
var CableCar = [
  ["path", { d: "M10 3h.01" }],
  ["path", { d: "M14 2h.01" }],
  ["path", { d: "m2 9 20-5" }],
  ["path", { d: "M12 12V6.5" }],
  ["rect", { width: "16", height: "10", x: "4", y: "12", rx: "3" }],
  ["path", { d: "M9 12v5" }],
  ["path", { d: "M15 12v5" }],
  ["path", { d: "M4 17h16" }]
];

// node_modules/lucide/dist/esm/icons/bus-front.js
var BusFront = [
  ["path", { d: "M4 6 2 7" }],
  ["path", { d: "M10 6h4" }],
  ["path", { d: "m22 7-2-1" }],
  ["rect", { width: "16", height: "16", x: "4", y: "3", rx: "2" }],
  ["path", { d: "M4 11h16" }],
  ["path", { d: "M8 15h.01" }],
  ["path", { d: "M16 15h.01" }],
  ["path", { d: "M6 19v2" }],
  ["path", { d: "M18 21v-2" }]
];

// node_modules/lucide/dist/esm/icons/cable.js
var Cable = [
  ["path", { d: "M17 21v-2a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1" }],
  ["path", { d: "M19 15V6.5a1 1 0 0 0-7 0v11a1 1 0 0 1-7 0V9" }],
  ["path", { d: "M21 21v-2h-4" }],
  ["path", { d: "M3 5h4V3" }],
  ["path", { d: "M7 5a1 1 0 0 1 1 1v1a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1V3" }]
];

// node_modules/lucide/dist/esm/icons/cake-slice.js
var CakeSlice = [
  ["circle", { cx: "9", cy: "7", r: "2" }],
  ["path", { d: "M7.2 7.9 3 11v9c0 .6.4 1 1 1h16c.6 0 1-.4 1-1v-9c0-2-3-6-7-8l-3.6 2.6" }],
  ["path", { d: "M16 13H3" }],
  ["path", { d: "M16 17H3" }]
];

// node_modules/lucide/dist/esm/icons/cake.js
var Cake = [
  ["path", { d: "M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8" }],
  ["path", { d: "M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1" }],
  ["path", { d: "M2 21h20" }],
  ["path", { d: "M7 8v3" }],
  ["path", { d: "M12 8v3" }],
  ["path", { d: "M17 8v3" }],
  ["path", { d: "M7 4h.01" }],
  ["path", { d: "M12 4h.01" }],
  ["path", { d: "M17 4h.01" }]
];

// node_modules/lucide/dist/esm/icons/calendar-1.js
var Calendar1 = [
  ["path", { d: "M11 14h1v4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }],
  ["rect", { x: "3", y: "4", width: "18", height: "18", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/calendar-arrow-down.js
var CalendarArrowDown = [
  ["path", { d: "m14 18 4 4 4-4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M18 14v8" }],
  ["path", { d: "M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }]
];

// node_modules/lucide/dist/esm/icons/calculator.js
var Calculator = [
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2" }],
  ["line", { x1: "8", x2: "16", y1: "6", y2: "6" }],
  ["line", { x1: "16", x2: "16", y1: "14", y2: "18" }],
  ["path", { d: "M16 10h.01" }],
  ["path", { d: "M12 10h.01" }],
  ["path", { d: "M8 10h.01" }],
  ["path", { d: "M12 14h.01" }],
  ["path", { d: "M8 14h.01" }],
  ["path", { d: "M12 18h.01" }],
  ["path", { d: "M8 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/calendar-arrow-up.js
var CalendarArrowUp = [
  ["path", { d: "m14 18 4-4 4 4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M18 22v-8" }],
  ["path", { d: "M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-check-2.js
var CalendarCheck2 = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "m16 20 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-check.js
var CalendarCheck = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "m9 16 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-clock.js
var CalendarClock = [
  ["path", { d: "M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M3 10h5" }],
  ["path", { d: "M17.5 17.5 16 16.3V14" }],
  ["circle", { cx: "16", cy: "16", r: "6" }]
];

// node_modules/lucide/dist/esm/icons/calendar-cog.js
var CalendarCog = [
  ["path", { d: "m15.228 16.852-.923-.383" }],
  ["path", { d: "m15.228 19.148-.923.383" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "m16.47 14.305.382.923" }],
  ["path", { d: "m16.852 20.772-.383.924" }],
  ["path", { d: "m19.148 15.228.383-.923" }],
  ["path", { d: "m19.53 21.696-.382-.924" }],
  ["path", { d: "m20.772 16.852.924-.383" }],
  ["path", { d: "m20.772 19.148.924.383" }],
  ["path", { d: "M21 11V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }],
  ["circle", { cx: "18", cy: "18", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/calendar-days.js
var CalendarDays = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 14h.01" }],
  ["path", { d: "M12 14h.01" }],
  ["path", { d: "M16 14h.01" }],
  ["path", { d: "M8 18h.01" }],
  ["path", { d: "M12 18h.01" }],
  ["path", { d: "M16 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/calendar-fold.js
var CalendarFold = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M21 17V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11Z" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M15 22v-4a2 2 0 0 1 2-2h4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-heart.js
var CalendarHeart = [
  ["path", { d: "M3 10h18V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7" }],
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  [
    "path",
    {
      d: "M21.29 14.7a2.43 2.43 0 0 0-2.65-.52c-.3.12-.57.3-.8.53l-.34.34-.35-.34a2.43 2.43 0 0 0-2.65-.53c-.3.12-.56.3-.79.53-.95.94-1 2.53.2 3.74L17.5 22l3.6-3.55c1.2-1.21 1.14-2.8.19-3.74Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/calendar-minus-2.js
var CalendarMinus2 = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M10 16h4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-minus.js
var CalendarMinus = [
  ["path", { d: "M16 19h6" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M21 15V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-off.js
var CalendarOff = [
  ["path", { d: "M4.2 4.2A2 2 0 0 0 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 1.82-1.18" }],
  ["path", { d: "M21 15.5V6a2 2 0 0 0-2-2H9.5" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M3 10h7" }],
  ["path", { d: "M21 10h-5.5" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/calendar-plus-2.js
var CalendarPlus2 = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M10 16h4" }],
  ["path", { d: "M12 14v4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-plus.js
var CalendarPlus = [
  ["path", { d: "M16 19h6" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M19 16v6" }],
  ["path", { d: "M21 12.598V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-range.js
var CalendarRange = [
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M17 14h-6" }],
  ["path", { d: "M13 18H7" }],
  ["path", { d: "M7 14h.01" }],
  ["path", { d: "M17 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/calendar-search.js
var CalendarSearch = [
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M21 11.75V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.25" }],
  ["path", { d: "m22 22-1.875-1.875" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "M8 2v4" }],
  ["circle", { cx: "18", cy: "18", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/calendar-sync.js
var CalendarSync = [
  ["path", { d: "M11 10v4h4" }],
  ["path", { d: "m11 14 1.535-1.605a5 5 0 0 1 8 1.5" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "m21 18-1.535 1.605a5 5 0 0 1-8-1.5" }],
  ["path", { d: "M21 22v-4h-4" }],
  ["path", { d: "M21 8.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4.3" }],
  ["path", { d: "M3 10h4" }],
  ["path", { d: "M8 2v4" }]
];

// node_modules/lucide/dist/esm/icons/calendar-x-2.js
var CalendarX2 = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "m17 22 5-5" }],
  ["path", { d: "m17 17 5 5" }]
];

// node_modules/lucide/dist/esm/icons/calendar-x.js
var CalendarX = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M3 10h18" }],
  ["path", { d: "m14 14-4 4" }],
  ["path", { d: "m10 14 4 4" }]
];

// node_modules/lucide/dist/esm/icons/calendar.js
var Calendar = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "4", rx: "2" }],
  ["path", { d: "M3 10h18" }]
];

// node_modules/lucide/dist/esm/icons/camera-off.js
var CameraOff = [
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }],
  ["path", { d: "M7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M9.5 4h5L17 7h3a2 2 0 0 1 2 2v7.5" }],
  ["path", { d: "M14.121 15.121A3 3 0 1 1 9.88 10.88" }]
];

// node_modules/lucide/dist/esm/icons/camera.js
var Camera = [
  [
    "path",
    {
      d: "M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"
    }
  ],
  ["circle", { cx: "12", cy: "13", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/candy-cane.js
var CandyCane = [
  [
    "path",
    { d: "M5.7 21a2 2 0 0 1-3.5-2l8.6-14a6 6 0 0 1 10.4 6 2 2 0 1 1-3.464-2 2 2 0 1 0-3.464-2Z" }
  ],
  ["path", { d: "M17.75 7 15 2.1" }],
  ["path", { d: "M10.9 4.8 13 9" }],
  ["path", { d: "m7.9 9.7 2 4.4" }],
  ["path", { d: "M4.9 14.7 7 18.9" }]
];

// node_modules/lucide/dist/esm/icons/candy-off.js
var CandyOff = [
  ["path", { d: "M10 10v7.9" }],
  ["path", { d: "M11.802 6.145a5 5 0 0 1 6.053 6.053" }],
  ["path", { d: "M14 6.1v2.243" }],
  ["path", { d: "m15.5 15.571-.964.964a5 5 0 0 1-7.071 0 5 5 0 0 1 0-7.07l.964-.965" }],
  [
    "path",
    {
      d: "M16 7V3a1 1 0 0 1 1.707-.707 2.5 2.5 0 0 0 2.152.717 1 1 0 0 1 1.131 1.131 2.5 2.5 0 0 0 .717 2.152A1 1 0 0 1 21 8h-4"
    }
  ],
  ["path", { d: "m2 2 20 20" }],
  [
    "path",
    {
      d: "M8 17v4a1 1 0 0 1-1.707.707 2.5 2.5 0 0 0-2.152-.717 1 1 0 0 1-1.131-1.131 2.5 2.5 0 0 0-.717-2.152A1 1 0 0 1 3 16h4"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/candy.js
var Candy = [
  ["path", { d: "M10 7v10.9" }],
  ["path", { d: "M14 6.1V17" }],
  [
    "path",
    {
      d: "M16 7V3a1 1 0 0 1 1.707-.707 2.5 2.5 0 0 0 2.152.717 1 1 0 0 1 1.131 1.131 2.5 2.5 0 0 0 .717 2.152A1 1 0 0 1 21 8h-4"
    }
  ],
  [
    "path",
    {
      d: "M16.536 7.465a5 5 0 0 0-7.072 0l-2 2a5 5 0 0 0 0 7.07 5 5 0 0 0 7.072 0l2-2a5 5 0 0 0 0-7.07"
    }
  ],
  [
    "path",
    {
      d: "M8 17v4a1 1 0 0 1-1.707.707 2.5 2.5 0 0 0-2.152-.717 1 1 0 0 1-1.131-1.131 2.5 2.5 0 0 0-.717-2.152A1 1 0 0 1 3 16h4"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/cannabis.js
var Cannabis = [
  ["path", { d: "M12 22v-4" }],
  [
    "path",
    {
      d: "M7 12c-1.5 0-4.5 1.5-5 3 3.5 1.5 6 1 6 1-1.5 1.5-2 3.5-2 5 2.5 0 4.5-1.5 6-3 1.5 1.5 3.5 3 6 3 0-1.5-.5-3.5-2-5 0 0 2.5.5 6-1-.5-1.5-3.5-3-5-3 1.5-1 4-4 4-6-2.5 0-5.5 1.5-7 3 0-2.5-.5-5-2-7-1.5 2-2 4.5-2 7-1.5-1.5-4.5-3-7-3 0 2 2.5 5 4 6"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/captions-off.js
var CaptionsOff = [
  ["path", { d: "M10.5 5H19a2 2 0 0 1 2 2v8.5" }],
  ["path", { d: "M17 11h-.5" }],
  ["path", { d: "M19 19H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M7 11h4" }],
  ["path", { d: "M7 15h2.5" }]
];

// node_modules/lucide/dist/esm/icons/car-front.js
var CarFront = [
  ["path", { d: "m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" }],
  ["path", { d: "M7 14h.01" }],
  ["path", { d: "M17 14h.01" }],
  ["rect", { width: "18", height: "8", x: "3", y: "10", rx: "2" }],
  ["path", { d: "M5 18v2" }],
  ["path", { d: "M19 18v2" }]
];

// node_modules/lucide/dist/esm/icons/captions.js
var Captions = [
  ["rect", { width: "18", height: "14", x: "3", y: "5", rx: "2", ry: "2" }],
  ["path", { d: "M7 15h4M15 15h2M7 11h2M13 11h4" }]
];

// node_modules/lucide/dist/esm/icons/car-taxi-front.js
var CarTaxiFront = [
  ["path", { d: "M10 2h4" }],
  ["path", { d: "m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8" }],
  ["path", { d: "M7 14h.01" }],
  ["path", { d: "M17 14h.01" }],
  ["rect", { width: "18", height: "8", x: "3", y: "10", rx: "2" }],
  ["path", { d: "M5 18v2" }],
  ["path", { d: "M19 18v2" }]
];

// node_modules/lucide/dist/esm/icons/car.js
var Car = [
  [
    "path",
    {
      d: "M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"
    }
  ],
  ["circle", { cx: "7", cy: "17", r: "2" }],
  ["path", { d: "M9 17h6" }],
  ["circle", { cx: "17", cy: "17", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/caravan.js
var Caravan = [
  ["path", { d: "M18 19V9a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v8a2 2 0 0 0 2 2h2" }],
  ["path", { d: "M2 9h3a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2" }],
  ["path", { d: "M22 17v1a1 1 0 0 1-1 1H10v-9a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v9" }],
  ["circle", { cx: "8", cy: "19", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/carrot.js
var Carrot = [
  [
    "path",
    {
      d: "M2.27 21.7s9.87-3.5 12.73-6.36a4.5 4.5 0 0 0-6.36-6.37C5.77 11.84 2.27 21.7 2.27 21.7zM8.64 14l-2.05-2.04M15.34 15l-2.46-2.46"
    }
  ],
  ["path", { d: "M22 9s-1.33-2-3.5-2C16.86 7 15 9 15 9s1.33 2 3.5 2S22 9 22 9z" }],
  ["path", { d: "M15 2s-2 1.33-2 3.5S15 9 15 9s2-1.84 2-3.5C17 3.33 15 2 15 2z" }]
];

// node_modules/lucide/dist/esm/icons/case-lower.js
var CaseLower = [
  ["circle", { cx: "7", cy: "12", r: "3" }],
  ["path", { d: "M10 9v6" }],
  ["circle", { cx: "17", cy: "12", r: "3" }],
  ["path", { d: "M14 7v8" }]
];

// node_modules/lucide/dist/esm/icons/case-sensitive.js
var CaseSensitive = [
  ["path", { d: "m3 15 4-8 4 8" }],
  ["path", { d: "M4 13h6" }],
  ["circle", { cx: "18", cy: "12", r: "3" }],
  ["path", { d: "M21 9v6" }]
];

// node_modules/lucide/dist/esm/icons/case-upper.js
var CaseUpper = [
  ["path", { d: "m3 15 4-8 4 8" }],
  ["path", { d: "M4 13h6" }],
  ["path", { d: "M15 11h4.5a2 2 0 0 1 0 4H15V7h4a2 2 0 0 1 0 4" }]
];

// node_modules/lucide/dist/esm/icons/cassette-tape.js
var CassetteTape = [
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["circle", { cx: "8", cy: "10", r: "2" }],
  ["path", { d: "M8 12h8" }],
  ["circle", { cx: "16", cy: "10", r: "2" }],
  ["path", { d: "m6 20 .7-2.9A1.4 1.4 0 0 1 8.1 16h7.8a1.4 1.4 0 0 1 1.4 1l.7 3" }]
];

// node_modules/lucide/dist/esm/icons/cast.js
var Cast = [
  ["path", { d: "M2 8V6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6" }],
  ["path", { d: "M2 12a9 9 0 0 1 8 8" }],
  ["path", { d: "M2 16a5 5 0 0 1 4 4" }],
  ["line", { x1: "2", x2: "2.01", y1: "20", y2: "20" }]
];

// node_modules/lucide/dist/esm/icons/castle.js
var Castle = [
  ["path", { d: "M22 20v-9H2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2Z" }],
  ["path", { d: "M18 11V4H6v7" }],
  ["path", { d: "M15 22v-4a3 3 0 0 0-3-3a3 3 0 0 0-3 3v4" }],
  ["path", { d: "M22 11V9" }],
  ["path", { d: "M2 11V9" }],
  ["path", { d: "M6 4V2" }],
  ["path", { d: "M18 4V2" }],
  ["path", { d: "M10 4V2" }],
  ["path", { d: "M14 4V2" }]
];

// node_modules/lucide/dist/esm/icons/cat.js
var Cat = [
  [
    "path",
    {
      d: "M12 5c.67 0 1.35.09 2 .26 1.78-2 5.03-2.84 6.42-2.26 1.4.58-.42 7-.42 7 .57 1.07 1 2.24 1 3.44C21 17.9 16.97 21 12 21s-9-3-9-7.56c0-1.25.5-2.4 1-3.44 0 0-1.89-6.42-.5-7 1.39-.58 4.72.23 6.5 2.23A9.04 9.04 0 0 1 12 5Z"
    }
  ],
  ["path", { d: "M8 14v.5" }],
  ["path", { d: "M16 14v.5" }],
  ["path", { d: "M11.25 16.25h1.5L12 17l-.75-.75Z" }]
];

// node_modules/lucide/dist/esm/icons/chart-area.js
var ChartArea = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  [
    "path",
    {
      d: "M7 11.207a.5.5 0 0 1 .146-.353l2-2a.5.5 0 0 1 .708 0l3.292 3.292a.5.5 0 0 0 .708 0l4.292-4.292a.5.5 0 0 1 .854.353V16a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/cctv.js
var Cctv = [
  [
    "path",
    { d: "M16.75 12h3.632a1 1 0 0 1 .894 1.447l-2.034 4.069a1 1 0 0 1-1.708.134l-2.124-2.97" }
  ],
  [
    "path",
    {
      d: "M17.106 9.053a1 1 0 0 1 .447 1.341l-3.106 6.211a1 1 0 0 1-1.342.447L3.61 12.3a2.92 2.92 0 0 1-1.3-3.91L3.69 5.6a2.92 2.92 0 0 1 3.92-1.3z"
    }
  ],
  ["path", { d: "M2 19h3.76a2 2 0 0 0 1.8-1.1L9 15" }],
  ["path", { d: "M2 21v-4" }],
  ["path", { d: "M7 9h.01" }]
];

// node_modules/lucide/dist/esm/icons/chart-bar-big.js
var ChartBarBig = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["rect", { x: "7", y: "13", width: "9", height: "4", rx: "1" }],
  ["rect", { x: "7", y: "5", width: "12", height: "4", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/chart-bar-decreasing.js
var ChartBarDecreasing = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M7 11h8" }],
  ["path", { d: "M7 16h3" }],
  ["path", { d: "M7 6h12" }]
];

// node_modules/lucide/dist/esm/icons/chart-bar-stacked.js
var ChartBarStacked = [
  ["path", { d: "M11 13v4" }],
  ["path", { d: "M15 5v4" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["rect", { x: "7", y: "13", width: "9", height: "4", rx: "1" }],
  ["rect", { x: "7", y: "5", width: "12", height: "4", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/chart-bar-increasing.js
var ChartBarIncreasing = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M7 11h8" }],
  ["path", { d: "M7 16h12" }],
  ["path", { d: "M7 6h3" }]
];

// node_modules/lucide/dist/esm/icons/chart-bar.js
var ChartBar = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M7 16h8" }],
  ["path", { d: "M7 11h12" }],
  ["path", { d: "M7 6h3" }]
];

// node_modules/lucide/dist/esm/icons/chart-candlestick.js
var ChartCandlestick = [
  ["path", { d: "M9 5v4" }],
  ["rect", { width: "4", height: "6", x: "7", y: "9", rx: "1" }],
  ["path", { d: "M9 15v2" }],
  ["path", { d: "M17 3v2" }],
  ["rect", { width: "4", height: "8", x: "15", y: "5", rx: "1" }],
  ["path", { d: "M17 13v3" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }]
];

// node_modules/lucide/dist/esm/icons/chart-column-big.js
var ChartColumnBig = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["rect", { x: "15", y: "5", width: "4", height: "12", rx: "1" }],
  ["rect", { x: "7", y: "8", width: "4", height: "9", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/chart-column-decreasing.js
var ChartColumnDecreasing = [
  ["path", { d: "M13 17V9" }],
  ["path", { d: "M18 17v-3" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M8 17V5" }]
];

// node_modules/lucide/dist/esm/icons/chart-column-increasing.js
var ChartColumnIncreasing = [
  ["path", { d: "M13 17V9" }],
  ["path", { d: "M18 17V5" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M8 17v-3" }]
];

// node_modules/lucide/dist/esm/icons/chart-column-stacked.js
var ChartColumnStacked = [
  ["path", { d: "M11 13H7" }],
  ["path", { d: "M19 9h-4" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["rect", { x: "15", y: "5", width: "4", height: "12", rx: "1" }],
  ["rect", { x: "7", y: "8", width: "4", height: "9", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/chart-column.js
var ChartColumn = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M18 17V9" }],
  ["path", { d: "M13 17V5" }],
  ["path", { d: "M8 17v-3" }]
];

// node_modules/lucide/dist/esm/icons/chart-gantt.js
var ChartGantt = [
  ["path", { d: "M10 6h8" }],
  ["path", { d: "M12 16h6" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M8 11h7" }]
];

// node_modules/lucide/dist/esm/icons/chart-line.js
var ChartLine = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "m19 9-5 5-4-4-3 3" }]
];

// node_modules/lucide/dist/esm/icons/chart-network.js
var ChartNetwork = [
  ["path", { d: "m13.11 7.664 1.78 2.672" }],
  ["path", { d: "m14.162 12.788-3.324 1.424" }],
  ["path", { d: "m20 4-6.06 1.515" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["circle", { cx: "12", cy: "6", r: "2" }],
  ["circle", { cx: "16", cy: "12", r: "2" }],
  ["circle", { cx: "9", cy: "15", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/chart-no-axes-column-decreasing.js
var ChartNoAxesColumnDecreasing = [
  ["path", { d: "M12 20V10" }],
  ["path", { d: "M18 20v-4" }],
  ["path", { d: "M6 20V4" }]
];

// node_modules/lucide/dist/esm/icons/chart-no-axes-column-increasing.js
var ChartNoAxesColumnIncreasing = [
  ["line", { x1: "12", x2: "12", y1: "20", y2: "10" }],
  ["line", { x1: "18", x2: "18", y1: "20", y2: "4" }],
  ["line", { x1: "6", x2: "6", y1: "20", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/chart-no-axes-column.js
var ChartNoAxesColumn = [
  ["line", { x1: "18", x2: "18", y1: "20", y2: "10" }],
  ["line", { x1: "12", x2: "12", y1: "20", y2: "4" }],
  ["line", { x1: "6", x2: "6", y1: "20", y2: "14" }]
];

// node_modules/lucide/dist/esm/icons/chart-no-axes-combined.js
var ChartNoAxesCombined = [
  ["path", { d: "M12 16v5" }],
  ["path", { d: "M16 14v7" }],
  ["path", { d: "M20 10v11" }],
  ["path", { d: "m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15" }],
  ["path", { d: "M4 18v3" }],
  ["path", { d: "M8 14v7" }]
];

// node_modules/lucide/dist/esm/icons/chart-no-axes-gantt.js
var ChartNoAxesGantt = [
  ["path", { d: "M8 6h10" }],
  ["path", { d: "M6 12h9" }],
  ["path", { d: "M11 18h7" }]
];

// node_modules/lucide/dist/esm/icons/chart-pie.js
var ChartPie = [
  [
    "path",
    {
      d: "M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"
    }
  ],
  ["path", { d: "M21.21 15.89A10 10 0 1 1 8 2.83" }]
];

// node_modules/lucide/dist/esm/icons/chart-scatter.js
var ChartScatter = [
  ["circle", { cx: "7.5", cy: "7.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "18.5", cy: "5.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "11.5", cy: "11.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "7.5", cy: "16.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "17.5", cy: "14.5", r: ".5", fill: "currentColor" }],
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }]
];

// node_modules/lucide/dist/esm/icons/chart-spline.js
var ChartSpline = [
  ["path", { d: "M3 3v16a2 2 0 0 0 2 2h16" }],
  ["path", { d: "M7 16c.5-2 1.5-7 4-7 2 0 2 3 4 3 2.5 0 4.5-5 5-7" }]
];

// node_modules/lucide/dist/esm/icons/check-check.js
var CheckCheck = [
  ["path", { d: "M18 6 7 17l-5-5" }],
  ["path", { d: "m22 10-7.5 7.5L13 16" }]
];

// node_modules/lucide/dist/esm/icons/check.js
var Check = [["path", { d: "M20 6 9 17l-5-5" }]];

// node_modules/lucide/dist/esm/icons/chef-hat.js
var ChefHat = [
  [
    "path",
    {
      d: "M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"
    }
  ],
  ["path", { d: "M6 17h12" }]
];

// node_modules/lucide/dist/esm/icons/cherry.js
var Cherry = [
  ["path", { d: "M2 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z" }],
  ["path", { d: "M12 17a5 5 0 0 0 10 0c0-2.76-2.5-5-5-3-2.5-2-5 .24-5 3Z" }],
  ["path", { d: "M7 14c3.22-2.91 4.29-8.75 5-12 1.66 2.38 4.94 9 5 12" }],
  ["path", { d: "M22 9c-4.29 0-7.14-2.33-10-7 5.71 0 10 4.67 10 7Z" }]
];

// node_modules/lucide/dist/esm/icons/chevron-down.js
var ChevronDown = [["path", { d: "m6 9 6 6 6-6" }]];

// node_modules/lucide/dist/esm/icons/chevron-first.js
var ChevronFirst = [
  ["path", { d: "m17 18-6-6 6-6" }],
  ["path", { d: "M7 6v12" }]
];

// node_modules/lucide/dist/esm/icons/chevron-left.js
var ChevronLeft = [["path", { d: "m15 18-6-6 6-6" }]];

// node_modules/lucide/dist/esm/icons/chevron-last.js
var ChevronLast = [
  ["path", { d: "m7 18 6-6-6-6" }],
  ["path", { d: "M17 6v12" }]
];

// node_modules/lucide/dist/esm/icons/chevron-right.js
var ChevronRight = [["path", { d: "m9 18 6-6-6-6" }]];

// node_modules/lucide/dist/esm/icons/chevrons-down-up.js
var ChevronsDownUp = [
  ["path", { d: "m7 20 5-5 5 5" }],
  ["path", { d: "m7 4 5 5 5-5" }]
];

// node_modules/lucide/dist/esm/icons/chevron-up.js
var ChevronUp = [["path", { d: "m18 15-6-6-6 6" }]];

// node_modules/lucide/dist/esm/icons/chevrons-down.js
var ChevronsDown = [
  ["path", { d: "m7 6 5 5 5-5" }],
  ["path", { d: "m7 13 5 5 5-5" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-left-right-ellipsis.js
var ChevronsLeftRightEllipsis = [
  ["path", { d: "m18 8 4 4-4 4" }],
  ["path", { d: "m6 8-4 4 4 4" }],
  ["path", { d: "M8 12h.01" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M16 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-left-right.js
var ChevronsLeftRight = [
  ["path", { d: "m9 7-5 5 5 5" }],
  ["path", { d: "m15 7 5 5-5 5" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-left.js
var ChevronsLeft = [
  ["path", { d: "m11 17-5-5 5-5" }],
  ["path", { d: "m18 17-5-5 5-5" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-right-left.js
var ChevronsRightLeft = [
  ["path", { d: "m20 17-5-5 5-5" }],
  ["path", { d: "m4 17 5-5-5-5" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-up-down.js
var ChevronsUpDown = [
  ["path", { d: "m7 15 5 5 5-5" }],
  ["path", { d: "m7 9 5-5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-right.js
var ChevronsRight = [
  ["path", { d: "m6 17 5-5-5-5" }],
  ["path", { d: "m13 17 5-5-5-5" }]
];

// node_modules/lucide/dist/esm/icons/chevrons-up.js
var ChevronsUp = [
  ["path", { d: "m17 11-5-5-5 5" }],
  ["path", { d: "m17 18-5-5-5 5" }]
];

// node_modules/lucide/dist/esm/icons/chrome.js
var Chrome = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["line", { x1: "21.17", x2: "12", y1: "8", y2: "8" }],
  ["line", { x1: "3.95", x2: "8.54", y1: "6.06", y2: "14" }],
  ["line", { x1: "10.88", x2: "15.46", y1: "21.94", y2: "14" }]
];

// node_modules/lucide/dist/esm/icons/cigarette-off.js
var CigaretteOff = [
  ["path", { d: "M12 12H3a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h13" }],
  ["path", { d: "M18 8c0-2.5-2-2.5-2-5" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M21 12a1 1 0 0 1 1 1v2a1 1 0 0 1-.5.866" }],
  ["path", { d: "M22 8c0-2.5-2-2.5-2-5" }],
  ["path", { d: "M7 12v4" }]
];

// node_modules/lucide/dist/esm/icons/church.js
var Church = [
  ["path", { d: "M10 9h4" }],
  ["path", { d: "M12 7v5" }],
  ["path", { d: "M14 22v-4a2 2 0 0 0-4 0v4" }],
  [
    "path",
    {
      d: "M18 22V5.618a1 1 0 0 0-.553-.894l-4.553-2.277a2 2 0 0 0-1.788 0L6.553 4.724A1 1 0 0 0 6 5.618V22"
    }
  ],
  [
    "path",
    {
      d: "m18 7 3.447 1.724a1 1 0 0 1 .553.894V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9.618a1 1 0 0 1 .553-.894L6 7"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/cigarette.js
var Cigarette = [
  ["path", { d: "M17 12H3a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h14" }],
  ["path", { d: "M18 8c0-2.5-2-2.5-2-5" }],
  ["path", { d: "M21 16a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1" }],
  ["path", { d: "M22 8c0-2.5-2-2.5-2-5" }],
  ["path", { d: "M7 12v4" }]
];

// node_modules/lucide/dist/esm/icons/circle-alert.js
var CircleAlert = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["line", { x1: "12", x2: "12", y1: "8", y2: "12" }],
  ["line", { x1: "12", x2: "12.01", y1: "16", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-down.js
var CircleArrowDown = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M12 8v8" }],
  ["path", { d: "m8 12 4 4 4-4" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-left.js
var CircleArrowLeft = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M16 12H8" }],
  ["path", { d: "m12 8-4 4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-out-down-left.js
var CircleArrowOutDownLeft = [
  ["path", { d: "M2 12a10 10 0 1 1 10 10" }],
  ["path", { d: "m2 22 10-10" }],
  ["path", { d: "M8 22H2v-6" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-out-down-right.js
var CircleArrowOutDownRight = [
  ["path", { d: "M12 22a10 10 0 1 1 10-10" }],
  ["path", { d: "M22 22 12 12" }],
  ["path", { d: "M22 16v6h-6" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-out-up-left.js
var CircleArrowOutUpLeft = [
  ["path", { d: "M2 8V2h6" }],
  ["path", { d: "m2 2 10 10" }],
  ["path", { d: "M12 2A10 10 0 1 1 2 12" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-out-up-right.js
var CircleArrowOutUpRight = [
  ["path", { d: "M22 12A10 10 0 1 1 12 2" }],
  ["path", { d: "M22 2 12 12" }],
  ["path", { d: "M16 2h6v6" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-up.js
var CircleArrowUp = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m16 12-4-4-4 4" }],
  ["path", { d: "M12 16V8" }]
];

// node_modules/lucide/dist/esm/icons/circle-arrow-right.js
var CircleArrowRight = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "m12 16 4-4-4-4" }]
];

// node_modules/lucide/dist/esm/icons/circle-check-big.js
var CircleCheckBig = [
  ["path", { d: "M21.801 10A10 10 0 1 1 17 3.335" }],
  ["path", { d: "m9 11 3 3L22 4" }]
];

// node_modules/lucide/dist/esm/icons/circle-check.js
var CircleCheck = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m9 12 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/circle-chevron-down.js
var CircleChevronDown = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m16 10-4 4-4-4" }]
];

// node_modules/lucide/dist/esm/icons/circle-chevron-left.js
var CircleChevronLeft = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m14 16-4-4 4-4" }]
];

// node_modules/lucide/dist/esm/icons/circle-chevron-right.js
var CircleChevronRight = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m10 8 4 4-4 4" }]
];

// node_modules/lucide/dist/esm/icons/circle-chevron-up.js
var CircleChevronUp = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m8 14 4-4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/circle-dashed.js
var CircleDashed = [
  ["path", { d: "M10.1 2.182a10 10 0 0 1 3.8 0" }],
  ["path", { d: "M13.9 21.818a10 10 0 0 1-3.8 0" }],
  ["path", { d: "M17.609 3.721a10 10 0 0 1 2.69 2.7" }],
  ["path", { d: "M2.182 13.9a10 10 0 0 1 0-3.8" }],
  ["path", { d: "M20.279 17.609a10 10 0 0 1-2.7 2.69" }],
  ["path", { d: "M21.818 10.1a10 10 0 0 1 0 3.8" }],
  ["path", { d: "M3.721 6.391a10 10 0 0 1 2.7-2.69" }],
  ["path", { d: "M6.391 20.279a10 10 0 0 1-2.69-2.7" }]
];

// node_modules/lucide/dist/esm/icons/circle-divide.js
var CircleDivide = [
  ["line", { x1: "8", x2: "16", y1: "12", y2: "12" }],
  ["line", { x1: "12", x2: "12", y1: "16", y2: "16" }],
  ["line", { x1: "12", x2: "12", y1: "8", y2: "8" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/circle-dollar-sign.js
var CircleDollarSign = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" }],
  ["path", { d: "M12 18V6" }]
];

// node_modules/lucide/dist/esm/icons/circle-dot-dashed.js
var CircleDotDashed = [
  ["path", { d: "M10.1 2.18a9.93 9.93 0 0 1 3.8 0" }],
  ["path", { d: "M17.6 3.71a9.95 9.95 0 0 1 2.69 2.7" }],
  ["path", { d: "M21.82 10.1a9.93 9.93 0 0 1 0 3.8" }],
  ["path", { d: "M20.29 17.6a9.95 9.95 0 0 1-2.7 2.69" }],
  ["path", { d: "M13.9 21.82a9.94 9.94 0 0 1-3.8 0" }],
  ["path", { d: "M6.4 20.29a9.95 9.95 0 0 1-2.69-2.7" }],
  ["path", { d: "M2.18 13.9a9.93 9.93 0 0 1 0-3.8" }],
  ["path", { d: "M3.71 6.4a9.95 9.95 0 0 1 2.7-2.69" }],
  ["circle", { cx: "12", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/circle-dot.js
var CircleDot = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["circle", { cx: "12", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/circle-ellipsis.js
var CircleEllipsis = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M17 12h.01" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M7 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/circle-equal.js
var CircleEqual = [
  ["path", { d: "M7 10h10" }],
  ["path", { d: "M7 14h10" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/circle-fading-arrow-up.js
var CircleFadingArrowUp = [
  ["path", { d: "M12 2a10 10 0 0 1 7.38 16.75" }],
  ["path", { d: "m16 12-4-4-4 4" }],
  ["path", { d: "M12 16V8" }],
  ["path", { d: "M2.5 8.875a10 10 0 0 0-.5 3" }],
  ["path", { d: "M2.83 16a10 10 0 0 0 2.43 3.4" }],
  ["path", { d: "M4.636 5.235a10 10 0 0 1 .891-.857" }],
  ["path", { d: "M8.644 21.42a10 10 0 0 0 7.631-.38" }]
];

// node_modules/lucide/dist/esm/icons/circle-fading-plus.js
var CircleFadingPlus = [
  ["path", { d: "M12 2a10 10 0 0 1 7.38 16.75" }],
  ["path", { d: "M12 8v8" }],
  ["path", { d: "M16 12H8" }],
  ["path", { d: "M2.5 8.875a10 10 0 0 0-.5 3" }],
  ["path", { d: "M2.83 16a10 10 0 0 0 2.43 3.4" }],
  ["path", { d: "M4.636 5.235a10 10 0 0 1 .891-.857" }],
  ["path", { d: "M8.644 21.42a10 10 0 0 0 7.631-.38" }]
];

// node_modules/lucide/dist/esm/icons/circle-gauge.js
var CircleGauge = [
  ["path", { d: "M15.6 2.7a10 10 0 1 0 5.7 5.7" }],
  ["circle", { cx: "12", cy: "12", r: "2" }],
  ["path", { d: "M13.4 10.6 19 5" }]
];

// node_modules/lucide/dist/esm/icons/circle-help.js
var CircleHelp = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" }],
  ["path", { d: "M12 17h.01" }]
];

// node_modules/lucide/dist/esm/icons/circle-minus.js
var CircleMinus = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M8 12h8" }]
];

// node_modules/lucide/dist/esm/icons/circle-off.js
var CircleOff = [
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M8.35 2.69A10 10 0 0 1 21.3 15.65" }],
  ["path", { d: "M19.08 19.08A10 10 0 1 1 4.92 4.92" }]
];

// node_modules/lucide/dist/esm/icons/circle-parking-off.js
var CircleParkingOff = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m5 5 14 14" }],
  ["path", { d: "M13 13a3 3 0 1 0 0-6H9v2" }],
  ["path", { d: "M9 17v-2.34" }]
];

// node_modules/lucide/dist/esm/icons/circle-parking.js
var CircleParking = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M9 17V7h4a3 3 0 0 1 0 6H9" }]
];

// node_modules/lucide/dist/esm/icons/circle-pause.js
var CirclePause = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["line", { x1: "10", x2: "10", y1: "15", y2: "9" }],
  ["line", { x1: "14", x2: "14", y1: "15", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/circle-percent.js
var CirclePercent = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "M9 9h.01" }],
  ["path", { d: "M15 15h.01" }]
];

// node_modules/lucide/dist/esm/icons/circle-play.js
var CirclePlay = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polygon", { points: "10 8 16 12 10 16 10 8" }]
];

// node_modules/lucide/dist/esm/icons/circle-plus.js
var CirclePlus = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "M12 8v8" }]
];

// node_modules/lucide/dist/esm/icons/circle-power.js
var CirclePower = [
  ["path", { d: "M12 7v4" }],
  ["path", { d: "M7.998 9.003a5 5 0 1 0 8-.005" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/circle-slash-2.js
var CircleSlash2 = [
  ["path", { d: "M22 2 2 22" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/circle-slash.js
var CircleSlash = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["line", { x1: "9", x2: "15", y1: "15", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/circle-small.js
var CircleSmall = [["circle", { cx: "12", cy: "12", r: "6" }]];

// node_modules/lucide/dist/esm/icons/circle-stop.js
var CircleStop = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["rect", { x: "9", y: "9", width: "6", height: "6", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/circle-user-round.js
var CircleUserRound = [
  ["path", { d: "M18 20a6 6 0 0 0-12 0" }],
  ["circle", { cx: "12", cy: "10", r: "4" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/circle-user.js
var CircleUser = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" }]
];

// node_modules/lucide/dist/esm/icons/circle.js
var Circle = [["circle", { cx: "12", cy: "12", r: "10" }]];

// node_modules/lucide/dist/esm/icons/circuit-board.js
var CircuitBoard = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M11 9h4a2 2 0 0 0 2-2V3" }],
  ["circle", { cx: "9", cy: "9", r: "2" }],
  ["path", { d: "M7 21v-4a2 2 0 0 1 2-2h4" }],
  ["circle", { cx: "15", cy: "15", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/circle-x.js
var CircleX = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "m9 9 6 6" }]
];

// node_modules/lucide/dist/esm/icons/citrus.js
var Citrus = [
  [
    "path",
    { d: "M21.66 17.67a1.08 1.08 0 0 1-.04 1.6A12 12 0 0 1 4.73 2.38a1.1 1.1 0 0 1 1.61-.04z" }
  ],
  ["path", { d: "M19.65 15.66A8 8 0 0 1 8.35 4.34" }],
  ["path", { d: "m14 10-5.5 5.5" }],
  ["path", { d: "M14 17.85V10H6.15" }]
];

// node_modules/lucide/dist/esm/icons/clapperboard.js
var Clapperboard = [
  ["path", { d: "M20.2 6 3 11l-.9-2.4c-.3-1.1.3-2.2 1.3-2.5l13.5-4c1.1-.3 2.2.3 2.5 1.3Z" }],
  ["path", { d: "m6.2 5.3 3.1 3.9" }],
  ["path", { d: "m12.4 3.4 3.1 4" }],
  ["path", { d: "M3 11h18v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-check.js
var ClipboardCheck = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "m9 14 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-copy.js
var ClipboardCopy = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v4" }],
  ["path", { d: "M21 14H11" }],
  ["path", { d: "m15 10-4 4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-list.js
var ClipboardList = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M12 11h4" }],
  ["path", { d: "M12 16h4" }],
  ["path", { d: "M8 11h.01" }],
  ["path", { d: "M8 16h.01" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-minus.js
var ClipboardMinus = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M9 14h6" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-paste.js
var ClipboardPaste = [
  ["path", { d: "M11 14h10" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v1.344" }],
  ["path", { d: "m17 18 4-4-4-4" }],
  ["path", { d: "M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 1.793-1.113" }],
  ["rect", { x: "8", y: "2", width: "8", height: "4", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-pen-line.js
var ClipboardPenLine = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1" }],
  ["path", { d: "M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 1.73 1" }],
  ["path", { d: "M8 18h1" }],
  [
    "path",
    {
      d: "M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/clipboard-pen.js
var ClipboardPen = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5.5" }],
  ["path", { d: "M4 13.5V6a2 2 0 0 1 2-2h2" }],
  [
    "path",
    {
      d: "M13.378 15.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/clipboard-plus.js
var ClipboardPlus = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M9 14h6" }],
  ["path", { d: "M12 17v-6" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-type.js
var ClipboardType = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M9 12v-1h6v1" }],
  ["path", { d: "M11 17h2" }],
  ["path", { d: "M12 11v6" }]
];

// node_modules/lucide/dist/esm/icons/clipboard-x.js
var ClipboardX = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "m15 11-6 6" }],
  ["path", { d: "m9 11 6 6" }]
];

// node_modules/lucide/dist/esm/icons/clipboard.js
var Clipboard = [
  ["rect", { width: "8", height: "4", x: "8", y: "2", rx: "1", ry: "1" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" }]
];

// node_modules/lucide/dist/esm/icons/clock-1.js
var Clock1 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 14.5 8" }]
];

// node_modules/lucide/dist/esm/icons/clock-10.js
var Clock10 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 8 10" }]
];

// node_modules/lucide/dist/esm/icons/clock-11.js
var Clock11 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 9.5 8" }]
];

// node_modules/lucide/dist/esm/icons/clock-12.js
var Clock12 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12" }]
];

// node_modules/lucide/dist/esm/icons/clock-2.js
var Clock2 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 16 10" }]
];

// node_modules/lucide/dist/esm/icons/clock-3.js
var Clock3 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 16.5 12" }]
];

// node_modules/lucide/dist/esm/icons/clock-4.js
var Clock4 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 16 14" }]
];

// node_modules/lucide/dist/esm/icons/clock-5.js
var Clock5 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 14.5 16" }]
];

// node_modules/lucide/dist/esm/icons/clock-6.js
var Clock6 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 12 16.5" }]
];

// node_modules/lucide/dist/esm/icons/clock-7.js
var Clock7 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 9.5 16" }]
];

// node_modules/lucide/dist/esm/icons/clock-9.js
var Clock9 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 7.5 12" }]
];

// node_modules/lucide/dist/esm/icons/clock-8.js
var Clock8 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 8 14" }]
];

// node_modules/lucide/dist/esm/icons/clock-alert.js
var ClockAlert = [
  ["path", { d: "M12 6v6l4 2" }],
  ["path", { d: "M16 21.16a10 10 0 1 1 5-13.516" }],
  ["path", { d: "M20 11.5v6" }],
  ["path", { d: "M20 21.5h.01" }]
];

// node_modules/lucide/dist/esm/icons/clock-arrow-down.js
var ClockArrowDown = [
  ["path", { d: "M12.338 21.994A10 10 0 1 1 21.925 13.227" }],
  ["path", { d: "M12 6v6l2 1" }],
  ["path", { d: "m14 18 4 4 4-4" }],
  ["path", { d: "M18 14v8" }]
];

// node_modules/lucide/dist/esm/icons/clock-arrow-up.js
var ClockArrowUp = [
  ["path", { d: "M13.228 21.925A10 10 0 1 1 21.994 12.338" }],
  ["path", { d: "M12 6v6l1.562.781" }],
  ["path", { d: "m14 18 4-4 4 4" }],
  ["path", { d: "M18 22v-8" }]
];

// node_modules/lucide/dist/esm/icons/clock-fading.js
var ClockFading = [
  ["path", { d: "M12 2a10 10 0 0 1 7.38 16.75" }],
  ["path", { d: "M12 6v6l4 2" }],
  ["path", { d: "M2.5 8.875a10 10 0 0 0-.5 3" }],
  ["path", { d: "M2.83 16a10 10 0 0 0 2.43 3.4" }],
  ["path", { d: "M4.636 5.235a10 10 0 0 1 .891-.857" }],
  ["path", { d: "M8.644 21.42a10 10 0 0 0 7.631-.38" }]
];

// node_modules/lucide/dist/esm/icons/clock-plus.js
var ClockPlus = [
  ["path", { d: "M12 6v6l3.644 1.822" }],
  ["path", { d: "M16 19h6" }],
  ["path", { d: "M19 16v6" }],
  ["path", { d: "M21.92 13.267a10 10 0 1 0-8.653 8.653" }]
];

// node_modules/lucide/dist/esm/icons/clock.js
var Clock = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["polyline", { points: "12 6 12 12 16 14" }]
];

// node_modules/lucide/dist/esm/icons/cloud-alert.js
var CloudAlert = [
  ["path", { d: "M12 12v4" }],
  ["path", { d: "M12 20h.01" }],
  ["path", { d: "M17 18h.5a1 1 0 0 0 0-9h-1.79A7 7 0 1 0 7 17.708" }]
];

// node_modules/lucide/dist/esm/icons/cloud-cog.js
var CloudCog = [
  ["path", { d: "m10.852 19.772-.383.924" }],
  ["path", { d: "m13.148 14.228.383-.923" }],
  ["path", { d: "M13.148 19.772a3 3 0 1 0-2.296-5.544l-.383-.923" }],
  ["path", { d: "m13.53 20.696-.382-.924a3 3 0 1 1-2.296-5.544" }],
  ["path", { d: "m14.772 15.852.923-.383" }],
  ["path", { d: "m14.772 18.148.923.383" }],
  ["path", { d: "M4.2 15.1a7 7 0 1 1 9.93-9.858A7 7 0 0 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.2" }],
  ["path", { d: "m9.228 15.852-.923-.383" }],
  ["path", { d: "m9.228 18.148-.923.383" }]
];

// node_modules/lucide/dist/esm/icons/cloud-download.js
var CloudDownload = [
  ["path", { d: "M12 13v8l-4-4" }],
  ["path", { d: "m12 21 4-4" }],
  ["path", { d: "M4.393 15.269A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.436 8.284" }]
];

// node_modules/lucide/dist/esm/icons/cloud-fog.js
var CloudFog = [
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "M16 17H7" }],
  ["path", { d: "M17 21H9" }]
];

// node_modules/lucide/dist/esm/icons/cloud-drizzle.js
var CloudDrizzle = [
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "M8 19v1" }],
  ["path", { d: "M8 14v1" }],
  ["path", { d: "M16 19v1" }],
  ["path", { d: "M16 14v1" }],
  ["path", { d: "M12 21v1" }],
  ["path", { d: "M12 16v1" }]
];

// node_modules/lucide/dist/esm/icons/cloud-hail.js
var CloudHail = [
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "M16 14v2" }],
  ["path", { d: "M8 14v2" }],
  ["path", { d: "M16 20h.01" }],
  ["path", { d: "M8 20h.01" }],
  ["path", { d: "M12 16v2" }],
  ["path", { d: "M12 22h.01" }]
];

// node_modules/lucide/dist/esm/icons/cloud-lightning.js
var CloudLightning = [
  ["path", { d: "M6 16.326A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 .5 8.973" }],
  ["path", { d: "m13 12-3 5h4l-3 5" }]
];

// node_modules/lucide/dist/esm/icons/cloud-moon-rain.js
var CloudMoonRain = [
  ["path", { d: "M10.188 8.5A6 6 0 0 1 16 4a1 1 0 0 0 6 6 6 6 0 0 1-3 5.197" }],
  ["path", { d: "M11 20v2" }],
  ["path", { d: "M3 20a5 5 0 1 1 8.9-4H13a3 3 0 0 1 2 5.24" }],
  ["path", { d: "M7 19v2" }]
];

// node_modules/lucide/dist/esm/icons/cloud-moon.js
var CloudMoon = [
  ["path", { d: "M10.188 8.5A6 6 0 0 1 16 4a1 1 0 0 0 6 6 6 6 0 0 1-3 5.197" }],
  ["path", { d: "M13 16a3 3 0 1 1 0 6H7a5 5 0 1 1 4.9-6Z" }]
];

// node_modules/lucide/dist/esm/icons/cloud-off.js
var CloudOff = [
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M5.782 5.782A7 7 0 0 0 9 19h8.5a4.5 4.5 0 0 0 1.307-.193" }],
  ["path", { d: "M21.532 16.5A4.5 4.5 0 0 0 17.5 10h-1.79A7.008 7.008 0 0 0 10 5.07" }]
];

// node_modules/lucide/dist/esm/icons/cloud-rain-wind.js
var CloudRainWind = [
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "m9.2 22 3-7" }],
  ["path", { d: "m9 13-3 7" }],
  ["path", { d: "m17 13-3 7" }]
];

// node_modules/lucide/dist/esm/icons/cloud-rain.js
var CloudRain = [
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "M16 14v6" }],
  ["path", { d: "M8 14v6" }],
  ["path", { d: "M12 16v6" }]
];

// node_modules/lucide/dist/esm/icons/cloud-snow.js
var CloudSnow = [
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "M8 15h.01" }],
  ["path", { d: "M8 19h.01" }],
  ["path", { d: "M12 17h.01" }],
  ["path", { d: "M12 21h.01" }],
  ["path", { d: "M16 15h.01" }],
  ["path", { d: "M16 19h.01" }]
];

// node_modules/lucide/dist/esm/icons/cloud-sun-rain.js
var CloudSunRain = [
  ["path", { d: "M12 2v2" }],
  ["path", { d: "m4.93 4.93 1.41 1.41" }],
  ["path", { d: "M20 12h2" }],
  ["path", { d: "m19.07 4.93-1.41 1.41" }],
  ["path", { d: "M15.947 12.65a4 4 0 0 0-5.925-4.128" }],
  ["path", { d: "M3 20a5 5 0 1 1 8.9-4H13a3 3 0 0 1 2 5.24" }],
  ["path", { d: "M11 20v2" }],
  ["path", { d: "M7 19v2" }]
];

// node_modules/lucide/dist/esm/icons/cloud-sun.js
var CloudSun = [
  ["path", { d: "M12 2v2" }],
  ["path", { d: "m4.93 4.93 1.41 1.41" }],
  ["path", { d: "M20 12h2" }],
  ["path", { d: "m19.07 4.93-1.41 1.41" }],
  ["path", { d: "M15.947 12.65a4 4 0 0 0-5.925-4.128" }],
  ["path", { d: "M13 22H7a5 5 0 1 1 4.9-6H13a3 3 0 0 1 0 6Z" }]
];

// node_modules/lucide/dist/esm/icons/cloud-upload.js
var CloudUpload = [
  ["path", { d: "M12 13v8" }],
  ["path", { d: "M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" }],
  ["path", { d: "m8 17 4-4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/cloud.js
var Cloud = [["path", { d: "M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z" }]];

// node_modules/lucide/dist/esm/icons/clover.js
var Clover = [
  ["path", { d: "M16.17 7.83 2 22" }],
  [
    "path",
    {
      d: "M4.02 12a2.827 2.827 0 1 1 3.81-4.17A2.827 2.827 0 1 1 12 4.02a2.827 2.827 0 1 1 4.17 3.81A2.827 2.827 0 1 1 19.98 12a2.827 2.827 0 1 1-3.81 4.17A2.827 2.827 0 1 1 12 19.98a2.827 2.827 0 1 1-4.17-3.81A1 1 0 1 1 4 12"
    }
  ],
  ["path", { d: "m7.83 7.83 8.34 8.34" }]
];

// node_modules/lucide/dist/esm/icons/cloudy.js
var Cloudy = [
  ["path", { d: "M17.5 21H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z" }],
  ["path", { d: "M22 10a3 3 0 0 0-3-3h-2.207a5.502 5.502 0 0 0-10.702.5" }]
];

// node_modules/lucide/dist/esm/icons/club.js
var Club = [
  [
    "path",
    { d: "M17.28 9.05a5.5 5.5 0 1 0-10.56 0A5.5 5.5 0 1 0 12 17.66a5.5 5.5 0 1 0 5.28-8.6Z" }
  ],
  ["path", { d: "M12 17.66L12 22" }]
];

// node_modules/lucide/dist/esm/icons/code-xml.js
var CodeXml = [
  ["path", { d: "m18 16 4-4-4-4" }],
  ["path", { d: "m6 8-4 4 4 4" }],
  ["path", { d: "m14.5 4-5 16" }]
];

// node_modules/lucide/dist/esm/icons/code.js
var Code = [
  ["polyline", { points: "16 18 22 12 16 6" }],
  ["polyline", { points: "8 6 2 12 8 18" }]
];

// node_modules/lucide/dist/esm/icons/codepen.js
var Codepen = [
  ["polygon", { points: "12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "15.5" }],
  ["polyline", { points: "22 8.5 12 15.5 2 8.5" }],
  ["polyline", { points: "2 15.5 12 8.5 22 15.5" }],
  ["line", { x1: "12", x2: "12", y1: "2", y2: "8.5" }]
];

// node_modules/lucide/dist/esm/icons/codesandbox.js
var Codesandbox = [
  [
    "path",
    {
      d: "M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
    }
  ],
  ["polyline", { points: "7.5 4.21 12 6.81 16.5 4.21" }],
  ["polyline", { points: "7.5 19.79 7.5 14.6 3 12" }],
  ["polyline", { points: "21 12 16.5 14.6 16.5 19.79" }],
  ["polyline", { points: "3.27 6.96 12 12.01 20.73 6.96" }],
  ["line", { x1: "12", x2: "12", y1: "22.08", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/coffee.js
var Coffee = [
  ["path", { d: "M10 2v2" }],
  ["path", { d: "M14 2v2" }],
  [
    "path",
    {
      d: "M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"
    }
  ],
  ["path", { d: "M6 2v2" }]
];

// node_modules/lucide/dist/esm/icons/cog.js
var Cog = [
  ["path", { d: "M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" }],
  ["path", { d: "M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" }],
  ["path", { d: "M12 2v2" }],
  ["path", { d: "M12 22v-2" }],
  ["path", { d: "m17 20.66-1-1.73" }],
  ["path", { d: "M11 10.27 7 3.34" }],
  ["path", { d: "m20.66 17-1.73-1" }],
  ["path", { d: "m3.34 7 1.73 1" }],
  ["path", { d: "M14 12h8" }],
  ["path", { d: "M2 12h2" }],
  ["path", { d: "m20.66 7-1.73 1" }],
  ["path", { d: "m3.34 17 1.73-1" }],
  ["path", { d: "m17 3.34-1 1.73" }],
  ["path", { d: "m11 13.73-4 6.93" }]
];

// node_modules/lucide/dist/esm/icons/coins.js
var Coins = [
  ["circle", { cx: "8", cy: "8", r: "6" }],
  ["path", { d: "M18.09 10.37A6 6 0 1 1 10.34 18" }],
  ["path", { d: "M7 6h1v4" }],
  ["path", { d: "m16.71 13.88.7.71-2.82 2.82" }]
];

// node_modules/lucide/dist/esm/icons/columns-2.js
var Columns2 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M12 3v18" }]
];

// node_modules/lucide/dist/esm/icons/columns-3-cog.js
var Columns3Cog = [
  ["path", { d: "M10.5 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5.5" }],
  ["path", { d: "m14.3 19.6 1-.4" }],
  ["path", { d: "M15 3v7.5" }],
  ["path", { d: "m15.2 16.9-.9-.3" }],
  ["path", { d: "m16.6 21.7.3-.9" }],
  ["path", { d: "m16.8 15.3-.4-1" }],
  ["path", { d: "m19.1 15.2.3-.9" }],
  ["path", { d: "m19.6 21.7-.4-1" }],
  ["path", { d: "m20.7 16.8 1-.4" }],
  ["path", { d: "m21.7 19.4-.9-.3" }],
  ["path", { d: "M9 3v18" }],
  ["circle", { cx: "18", cy: "18", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/columns-3.js
var Columns3 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 3v18" }],
  ["path", { d: "M15 3v18" }]
];

// node_modules/lucide/dist/esm/icons/columns-4.js
var Columns4 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7.5 3v18" }],
  ["path", { d: "M12 3v18" }],
  ["path", { d: "M16.5 3v18" }]
];

// node_modules/lucide/dist/esm/icons/combine.js
var Combine = [
  ["path", { d: "M10 18H5a3 3 0 0 1-3-3v-1" }],
  ["path", { d: "M14 2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2" }],
  ["path", { d: "M20 2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2" }],
  ["path", { d: "m7 21 3-3-3-3" }],
  ["rect", { x: "14", y: "14", width: "8", height: "8", rx: "2" }],
  ["rect", { x: "2", y: "2", width: "8", height: "8", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/command.js
var Command = [
  ["path", { d: "M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3" }]
];

// node_modules/lucide/dist/esm/icons/component.js
var Component = [
  [
    "path",
    {
      d: "M15.536 11.293a1 1 0 0 0 0 1.414l2.376 2.377a1 1 0 0 0 1.414 0l2.377-2.377a1 1 0 0 0 0-1.414l-2.377-2.377a1 1 0 0 0-1.414 0z"
    }
  ],
  [
    "path",
    {
      d: "M2.297 11.293a1 1 0 0 0 0 1.414l2.377 2.377a1 1 0 0 0 1.414 0l2.377-2.377a1 1 0 0 0 0-1.414L6.088 8.916a1 1 0 0 0-1.414 0z"
    }
  ],
  [
    "path",
    {
      d: "M8.916 17.912a1 1 0 0 0 0 1.415l2.377 2.376a1 1 0 0 0 1.414 0l2.377-2.376a1 1 0 0 0 0-1.415l-2.377-2.376a1 1 0 0 0-1.414 0z"
    }
  ],
  [
    "path",
    {
      d: "M8.916 4.674a1 1 0 0 0 0 1.414l2.377 2.376a1 1 0 0 0 1.414 0l2.377-2.376a1 1 0 0 0 0-1.414l-2.377-2.377a1 1 0 0 0-1.414 0z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/compass.js
var Compass = [
  [
    "path",
    {
      d: "m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"
    }
  ],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/computer.js
var Computer = [
  ["rect", { width: "14", height: "8", x: "5", y: "2", rx: "2" }],
  ["rect", { width: "20", height: "8", x: "2", y: "14", rx: "2" }],
  ["path", { d: "M6 18h2" }],
  ["path", { d: "M12 18h6" }]
];

// node_modules/lucide/dist/esm/icons/concierge-bell.js
var ConciergeBell = [
  ["path", { d: "M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z" }],
  ["path", { d: "M20 16a8 8 0 1 0-16 0" }],
  ["path", { d: "M12 4v4" }],
  ["path", { d: "M10 4h4" }]
];

// node_modules/lucide/dist/esm/icons/cone.js
var Cone = [
  ["path", { d: "m20.9 18.55-8-15.98a1 1 0 0 0-1.8 0l-8 15.98" }],
  ["ellipse", { cx: "12", cy: "19", rx: "9", ry: "3" }]
];

// node_modules/lucide/dist/esm/icons/construction.js
var Construction = [
  ["rect", { x: "2", y: "6", width: "20", height: "8", rx: "1" }],
  ["path", { d: "M17 14v7" }],
  ["path", { d: "M7 14v7" }],
  ["path", { d: "M17 3v3" }],
  ["path", { d: "M7 3v3" }],
  ["path", { d: "M10 14 2.3 6.3" }],
  ["path", { d: "m14 6 7.7 7.7" }],
  ["path", { d: "m8 6 8 8" }]
];

// node_modules/lucide/dist/esm/icons/contact-round.js
var ContactRound = [
  ["path", { d: "M16 2v2" }],
  ["path", { d: "M17.915 22a6 6 0 0 0-12 0" }],
  ["path", { d: "M8 2v2" }],
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["rect", { x: "3", y: "4", width: "18", height: "18", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/contact.js
var Contact = [
  ["path", { d: "M16 2v2" }],
  ["path", { d: "M7 22v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M8 2v2" }],
  ["circle", { cx: "12", cy: "11", r: "3" }],
  ["rect", { x: "3", y: "4", width: "18", height: "18", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/container.js
var Container = [
  [
    "path",
    {
      d: "M22 7.7c0-.6-.4-1.2-.8-1.5l-6.3-3.9a1.72 1.72 0 0 0-1.7 0l-10.3 6c-.5.2-.9.8-.9 1.4v6.6c0 .5.4 1.2.8 1.5l6.3 3.9a1.72 1.72 0 0 0 1.7 0l10.3-6c.5-.3.9-1 .9-1.5Z"
    }
  ],
  ["path", { d: "M10 21.9V14L2.1 9.1" }],
  ["path", { d: "m10 14 11.9-6.9" }],
  ["path", { d: "M14 19.8v-8.1" }],
  ["path", { d: "M18 17.5V9.4" }]
];

// node_modules/lucide/dist/esm/icons/contrast.js
var Contrast = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M12 18a6 6 0 0 0 0-12v12z" }]
];

// node_modules/lucide/dist/esm/icons/cookie.js
var Cookie = [
  ["path", { d: "M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5" }],
  ["path", { d: "M8.5 8.5v.01" }],
  ["path", { d: "M16 15.5v.01" }],
  ["path", { d: "M12 12v.01" }],
  ["path", { d: "M11 17v.01" }],
  ["path", { d: "M7 14v.01" }]
];

// node_modules/lucide/dist/esm/icons/cooking-pot.js
var CookingPot = [
  ["path", { d: "M2 12h20" }],
  ["path", { d: "M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8" }],
  ["path", { d: "m4 8 16-4" }],
  ["path", { d: "m8.86 6.78-.45-1.81a2 2 0 0 1 1.45-2.43l1.94-.48a2 2 0 0 1 2.43 1.46l.45 1.8" }]
];

// node_modules/lucide/dist/esm/icons/copy-check.js
var CopyCheck = [
  ["path", { d: "m12 15 2 2 4-4" }],
  ["rect", { width: "14", height: "14", x: "8", y: "8", rx: "2", ry: "2" }],
  ["path", { d: "M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" }]
];

// node_modules/lucide/dist/esm/icons/copy-minus.js
var CopyMinus = [
  ["line", { x1: "12", x2: "18", y1: "15", y2: "15" }],
  ["rect", { width: "14", height: "14", x: "8", y: "8", rx: "2", ry: "2" }],
  ["path", { d: "M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" }]
];

// node_modules/lucide/dist/esm/icons/copy-plus.js
var CopyPlus = [
  ["line", { x1: "15", x2: "15", y1: "12", y2: "18" }],
  ["line", { x1: "12", x2: "18", y1: "15", y2: "15" }],
  ["rect", { width: "14", height: "14", x: "8", y: "8", rx: "2", ry: "2" }],
  ["path", { d: "M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" }]
];

// node_modules/lucide/dist/esm/icons/copy-slash.js
var CopySlash = [
  ["line", { x1: "12", x2: "18", y1: "18", y2: "12" }],
  ["rect", { width: "14", height: "14", x: "8", y: "8", rx: "2", ry: "2" }],
  ["path", { d: "M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" }]
];

// node_modules/lucide/dist/esm/icons/copy-x.js
var CopyX = [
  ["line", { x1: "12", x2: "18", y1: "12", y2: "18" }],
  ["line", { x1: "12", x2: "18", y1: "18", y2: "12" }],
  ["rect", { width: "14", height: "14", x: "8", y: "8", rx: "2", ry: "2" }],
  ["path", { d: "M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" }]
];

// node_modules/lucide/dist/esm/icons/copy.js
var Copy = [
  ["rect", { width: "14", height: "14", x: "8", y: "8", rx: "2", ry: "2" }],
  ["path", { d: "M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" }]
];

// node_modules/lucide/dist/esm/icons/copyleft.js
var Copyleft = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M9.17 14.83a4 4 0 1 0 0-5.66" }]
];

// node_modules/lucide/dist/esm/icons/copyright.js
var Copyright = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M14.83 14.83a4 4 0 1 1 0-5.66" }]
];

// node_modules/lucide/dist/esm/icons/corner-down-left.js
var CornerDownLeft = [
  ["polyline", { points: "9 10 4 15 9 20" }],
  ["path", { d: "M20 4v7a4 4 0 0 1-4 4H4" }]
];

// node_modules/lucide/dist/esm/icons/corner-down-right.js
var CornerDownRight = [
  ["polyline", { points: "15 10 20 15 15 20" }],
  ["path", { d: "M4 4v7a4 4 0 0 0 4 4h12" }]
];

// node_modules/lucide/dist/esm/icons/corner-left-down.js
var CornerLeftDown = [
  ["polyline", { points: "14 15 9 20 4 15" }],
  ["path", { d: "M20 4h-7a4 4 0 0 0-4 4v12" }]
];

// node_modules/lucide/dist/esm/icons/corner-left-up.js
var CornerLeftUp = [
  ["polyline", { points: "14 9 9 4 4 9" }],
  ["path", { d: "M20 20h-7a4 4 0 0 1-4-4V4" }]
];

// node_modules/lucide/dist/esm/icons/corner-right-down.js
var CornerRightDown = [
  ["polyline", { points: "10 15 15 20 20 15" }],
  ["path", { d: "M4 4h7a4 4 0 0 1 4 4v12" }]
];

// node_modules/lucide/dist/esm/icons/corner-right-up.js
var CornerRightUp = [
  ["polyline", { points: "10 9 15 4 20 9" }],
  ["path", { d: "M4 20h7a4 4 0 0 0 4-4V4" }]
];

// node_modules/lucide/dist/esm/icons/corner-up-left.js
var CornerUpLeft = [
  ["polyline", { points: "9 14 4 9 9 4" }],
  ["path", { d: "M20 20v-7a4 4 0 0 0-4-4H4" }]
];

// node_modules/lucide/dist/esm/icons/corner-up-right.js
var CornerUpRight = [
  ["polyline", { points: "15 14 20 9 15 4" }],
  ["path", { d: "M4 20v-7a4 4 0 0 1 4-4h12" }]
];

// node_modules/lucide/dist/esm/icons/cpu.js
var Cpu = [
  ["path", { d: "M12 20v2" }],
  ["path", { d: "M12 2v2" }],
  ["path", { d: "M17 20v2" }],
  ["path", { d: "M17 2v2" }],
  ["path", { d: "M2 12h2" }],
  ["path", { d: "M2 17h2" }],
  ["path", { d: "M2 7h2" }],
  ["path", { d: "M20 12h2" }],
  ["path", { d: "M20 17h2" }],
  ["path", { d: "M20 7h2" }],
  ["path", { d: "M7 20v2" }],
  ["path", { d: "M7 2v2" }],
  ["rect", { x: "4", y: "4", width: "16", height: "16", rx: "2" }],
  ["rect", { x: "8", y: "8", width: "8", height: "8", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/creative-commons.js
var CreativeCommons = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M10 9.3a2.8 2.8 0 0 0-3.5 1 3.1 3.1 0 0 0 0 3.4 2.7 2.7 0 0 0 3.5 1" }],
  ["path", { d: "M17 9.3a2.8 2.8 0 0 0-3.5 1 3.1 3.1 0 0 0 0 3.4 2.7 2.7 0 0 0 3.5 1" }]
];

// node_modules/lucide/dist/esm/icons/credit-card.js
var CreditCard = [
  ["rect", { width: "20", height: "14", x: "2", y: "5", rx: "2" }],
  ["line", { x1: "2", x2: "22", y1: "10", y2: "10" }]
];

// node_modules/lucide/dist/esm/icons/croissant.js
var Croissant = [
  [
    "path",
    {
      d: "m4.6 13.11 5.79-3.21c1.89-1.05 4.79 1.78 3.71 3.71l-3.22 5.81C8.8 23.16.79 15.23 4.6 13.11Z"
    }
  ],
  [
    "path",
    { d: "m10.5 9.5-1-2.29C9.2 6.48 8.8 6 8 6H4.5C2.79 6 2 6.5 2 8.5a7.71 7.71 0 0 0 2 4.83" }
  ],
  ["path", { d: "M8 6c0-1.55.24-4-2-4-2 0-2.5 2.17-2.5 4" }],
  [
    "path",
    {
      d: "m14.5 13.5 2.29 1c.73.3 1.21.7 1.21 1.5v3.5c0 1.71-.5 2.5-2.5 2.5a7.71 7.71 0 0 1-4.83-2"
    }
  ],
  ["path", { d: "M18 16c1.55 0 4-.24 4 2 0 2-2.17 2.5-4 2.5" }]
];

// node_modules/lucide/dist/esm/icons/crop.js
var Crop = [
  ["path", { d: "M6 2v14a2 2 0 0 0 2 2h14" }],
  ["path", { d: "M18 22V8a2 2 0 0 0-2-2H2" }]
];

// node_modules/lucide/dist/esm/icons/cross.js
var Cross = [
  [
    "path",
    {
      d: "M4 9a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4a1 1 0 0 1 1 1v4a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-4a1 1 0 0 1 1-1h4a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-4a1 1 0 0 1-1-1V4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4a1 1 0 0 1-1 1z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/crosshair.js
var Crosshair = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["line", { x1: "22", x2: "18", y1: "12", y2: "12" }],
  ["line", { x1: "6", x2: "2", y1: "12", y2: "12" }],
  ["line", { x1: "12", x2: "12", y1: "6", y2: "2" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "18" }]
];

// node_modules/lucide/dist/esm/icons/crown.js
var Crown = [
  [
    "path",
    {
      d: "M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"
    }
  ],
  ["path", { d: "M5 21h14" }]
];

// node_modules/lucide/dist/esm/icons/cuboid.js
var Cuboid = [
  [
    "path",
    {
      d: "m21.12 6.4-6.05-4.06a2 2 0 0 0-2.17-.05L2.95 8.41a2 2 0 0 0-.95 1.7v5.82a2 2 0 0 0 .88 1.66l6.05 4.07a2 2 0 0 0 2.17.05l9.95-6.12a2 2 0 0 0 .95-1.7V8.06a2 2 0 0 0-.88-1.66Z"
    }
  ],
  ["path", { d: "M10 22v-8L2.25 9.15" }],
  ["path", { d: "m10 14 11.77-6.87" }]
];

// node_modules/lucide/dist/esm/icons/cup-soda.js
var CupSoda = [
  ["path", { d: "m6 8 1.75 12.28a2 2 0 0 0 2 1.72h4.54a2 2 0 0 0 2-1.72L18 8" }],
  ["path", { d: "M5 8h14" }],
  ["path", { d: "M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0" }],
  ["path", { d: "m12 8 1-6h2" }]
];

// node_modules/lucide/dist/esm/icons/currency.js
var Currency = [
  ["circle", { cx: "12", cy: "12", r: "8" }],
  ["line", { x1: "3", x2: "6", y1: "3", y2: "6" }],
  ["line", { x1: "21", x2: "18", y1: "3", y2: "6" }],
  ["line", { x1: "3", x2: "6", y1: "21", y2: "18" }],
  ["line", { x1: "21", x2: "18", y1: "21", y2: "18" }]
];

// node_modules/lucide/dist/esm/icons/cylinder.js
var Cylinder = [
  ["ellipse", { cx: "12", cy: "5", rx: "9", ry: "3" }],
  ["path", { d: "M3 5v14a9 3 0 0 0 18 0V5" }]
];

// node_modules/lucide/dist/esm/icons/database-backup.js
var DatabaseBackup = [
  ["ellipse", { cx: "12", cy: "5", rx: "9", ry: "3" }],
  ["path", { d: "M3 12a9 3 0 0 0 5 2.69" }],
  ["path", { d: "M21 9.3V5" }],
  ["path", { d: "M3 5v14a9 3 0 0 0 6.47 2.88" }],
  ["path", { d: "M12 12v4h4" }],
  ["path", { d: "M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16" }]
];

// node_modules/lucide/dist/esm/icons/database-zap.js
var DatabaseZap = [
  ["ellipse", { cx: "12", cy: "5", rx: "9", ry: "3" }],
  ["path", { d: "M3 5V19A9 3 0 0 0 15 21.84" }],
  ["path", { d: "M21 5V8" }],
  ["path", { d: "M21 12L18 17H22L19 22" }],
  ["path", { d: "M3 12A9 3 0 0 0 14.59 14.87" }]
];

// node_modules/lucide/dist/esm/icons/database.js
var Database = [
  ["ellipse", { cx: "12", cy: "5", rx: "9", ry: "3" }],
  ["path", { d: "M3 5V19A9 3 0 0 0 21 19V5" }],
  ["path", { d: "M3 12A9 3 0 0 0 21 12" }]
];

// node_modules/lucide/dist/esm/icons/dam.js
var Dam = [
  ["path", { d: "M11 11.31c1.17.56 1.54 1.69 3.5 1.69 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" }],
  ["path", { d: "M11.75 18c.35.5 1.45 1 2.75 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" }],
  ["path", { d: "M2 10h4" }],
  ["path", { d: "M2 14h4" }],
  ["path", { d: "M2 18h4" }],
  ["path", { d: "M2 6h4" }],
  ["path", { d: "M7 3a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1L10 4a1 1 0 0 0-1-1z" }]
];

// node_modules/lucide/dist/esm/icons/decimals-arrow-left.js
var DecimalsArrowLeft = [
  ["path", { d: "m13 21-3-3 3-3" }],
  ["path", { d: "M20 18H10" }],
  ["path", { d: "M3 11h.01" }],
  ["rect", { x: "6", y: "3", width: "5", height: "8", rx: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/decimals-arrow-right.js
var DecimalsArrowRight = [
  ["path", { d: "M10 18h10" }],
  ["path", { d: "m17 21 3-3-3-3" }],
  ["path", { d: "M3 11h.01" }],
  ["rect", { x: "15", y: "3", width: "5", height: "8", rx: "2.5" }],
  ["rect", { x: "6", y: "3", width: "5", height: "8", rx: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/delete.js
var Delete = [
  [
    "path",
    {
      d: "M10 5a2 2 0 0 0-1.344.519l-6.328 5.74a1 1 0 0 0 0 1.481l6.328 5.741A2 2 0 0 0 10 19h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"
    }
  ],
  ["path", { d: "m12 9 6 6" }],
  ["path", { d: "m18 9-6 6" }]
];

// node_modules/lucide/dist/esm/icons/dessert.js
var Dessert = [
  ["circle", { cx: "12", cy: "4", r: "2" }],
  [
    "path",
    {
      d: "M10.2 3.2C5.5 4 2 8.1 2 13a2 2 0 0 0 4 0v-1a2 2 0 0 1 4 0v4a2 2 0 0 0 4 0v-4a2 2 0 0 1 4 0v1a2 2 0 0 0 4 0c0-4.9-3.5-9-8.2-9.8"
    }
  ],
  ["path", { d: "M3.2 14.8a9 9 0 0 0 17.6 0" }]
];

// node_modules/lucide/dist/esm/icons/diameter.js
var Diameter = [
  ["circle", { cx: "19", cy: "19", r: "2" }],
  ["circle", { cx: "5", cy: "5", r: "2" }],
  ["path", { d: "M6.48 3.66a10 10 0 0 1 13.86 13.86" }],
  ["path", { d: "m6.41 6.41 11.18 11.18" }],
  ["path", { d: "M3.66 6.48a10 10 0 0 0 13.86 13.86" }]
];

// node_modules/lucide/dist/esm/icons/diamond-minus.js
var DiamondMinus = [
  [
    "path",
    {
      d: "M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41L13.7 2.71a2.41 2.41 0 0 0-3.41 0z"
    }
  ],
  ["path", { d: "M8 12h8" }]
];

// node_modules/lucide/dist/esm/icons/diamond-percent.js
var DiamondPercent = [
  [
    "path",
    {
      d: "M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41L13.7 2.71a2.41 2.41 0 0 0-3.41 0Z"
    }
  ],
  ["path", { d: "M9.2 9.2h.01" }],
  ["path", { d: "m14.5 9.5-5 5" }],
  ["path", { d: "M14.7 14.8h.01" }]
];

// node_modules/lucide/dist/esm/icons/diamond-plus.js
var DiamondPlus = [
  ["path", { d: "M12 8v8" }],
  [
    "path",
    {
      d: "M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41L13.7 2.71a2.41 2.41 0 0 0-3.41 0z"
    }
  ],
  ["path", { d: "M8 12h8" }]
];

// node_modules/lucide/dist/esm/icons/diamond.js
var Diamond = [
  [
    "path",
    {
      d: "M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/dice-1.js
var Dice1 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M12 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/dice-2.js
var Dice2 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M15 9h.01" }],
  ["path", { d: "M9 15h.01" }]
];

// node_modules/lucide/dist/esm/icons/dice-3.js
var Dice3 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M16 8h.01" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M8 16h.01" }]
];

// node_modules/lucide/dist/esm/icons/dice-4.js
var Dice4 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M16 8h.01" }],
  ["path", { d: "M8 8h.01" }],
  ["path", { d: "M8 16h.01" }],
  ["path", { d: "M16 16h.01" }]
];

// node_modules/lucide/dist/esm/icons/dice-5.js
var Dice5 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M16 8h.01" }],
  ["path", { d: "M8 8h.01" }],
  ["path", { d: "M8 16h.01" }],
  ["path", { d: "M16 16h.01" }],
  ["path", { d: "M12 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/dice-6.js
var Dice6 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M16 8h.01" }],
  ["path", { d: "M16 12h.01" }],
  ["path", { d: "M16 16h.01" }],
  ["path", { d: "M8 8h.01" }],
  ["path", { d: "M8 12h.01" }],
  ["path", { d: "M8 16h.01" }]
];

// node_modules/lucide/dist/esm/icons/dices.js
var Dices = [
  ["rect", { width: "12", height: "12", x: "2", y: "10", rx: "2", ry: "2" }],
  ["path", { d: "m17.92 14 3.5-3.5a2.24 2.24 0 0 0 0-3l-5-4.92a2.24 2.24 0 0 0-3 0L10 6" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "M10 14h.01" }],
  ["path", { d: "M15 6h.01" }],
  ["path", { d: "M18 9h.01" }]
];

// node_modules/lucide/dist/esm/icons/diff.js
var Diff = [
  ["path", { d: "M12 3v14" }],
  ["path", { d: "M5 10h14" }],
  ["path", { d: "M5 21h14" }]
];

// node_modules/lucide/dist/esm/icons/disc-2.js
var Disc2 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["path", { d: "M12 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/disc-3.js
var Disc3 = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M6 12c0-1.7.7-3.2 1.8-4.2" }],
  ["circle", { cx: "12", cy: "12", r: "2" }],
  ["path", { d: "M18 12c0 1.7-.7 3.2-1.8 4.2" }]
];

// node_modules/lucide/dist/esm/icons/disc.js
var Disc = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/disc-album.js
var DiscAlbum = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["circle", { cx: "12", cy: "12", r: "5" }],
  ["path", { d: "M12 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/divide.js
var Divide = [
  ["circle", { cx: "12", cy: "6", r: "1" }],
  ["line", { x1: "5", x2: "19", y1: "12", y2: "12" }],
  ["circle", { cx: "12", cy: "18", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/dna-off.js
var DnaOff = [
  ["path", { d: "M15 2c-1.35 1.5-2.092 3-2.5 4.5L14 8" }],
  ["path", { d: "m17 6-2.891-2.891" }],
  ["path", { d: "M2 15c3.333-3 6.667-3 10-3" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "m20 9 .891.891" }],
  ["path", { d: "M22 9c-1.5 1.35-3 2.092-4.5 2.5l-1-1" }],
  ["path", { d: "M3.109 14.109 4 15" }],
  ["path", { d: "m6.5 12.5 1 1" }],
  ["path", { d: "m7 18 2.891 2.891" }],
  ["path", { d: "M9 22c1.35-1.5 2.092-3 2.5-4.5L10 16" }]
];

// node_modules/lucide/dist/esm/icons/dock.js
var Dock = [
  ["path", { d: "M2 8h20" }],
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["path", { d: "M6 16h12" }]
];

// node_modules/lucide/dist/esm/icons/dna.js
var Dna = [
  ["path", { d: "m10 16 1.5 1.5" }],
  ["path", { d: "m14 8-1.5-1.5" }],
  ["path", { d: "M15 2c-1.798 1.998-2.518 3.995-2.807 5.993" }],
  ["path", { d: "m16.5 10.5 1 1" }],
  ["path", { d: "m17 6-2.891-2.891" }],
  ["path", { d: "M2 15c6.667-6 13.333 0 20-6" }],
  ["path", { d: "m20 9 .891.891" }],
  ["path", { d: "M3.109 14.109 4 15" }],
  ["path", { d: "m6.5 12.5 1 1" }],
  ["path", { d: "m7 18 2.891 2.891" }],
  ["path", { d: "M9 22c1.798-1.998 2.518-3.995 2.807-5.993" }]
];

// node_modules/lucide/dist/esm/icons/dog.js
var Dog = [
  ["path", { d: "M11.25 16.25h1.5L12 17z" }],
  ["path", { d: "M16 14v.5" }],
  [
    "path",
    {
      d: "M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444a11.702 11.702 0 0 0-.493-3.309"
    }
  ],
  ["path", { d: "M8 14v.5" }],
  [
    "path",
    {
      d: "M8.5 8.5c-.384 1.05-1.083 2.028-2.344 2.5-1.931.722-3.576-.297-3.656-1-.113-.994 1.177-6.53 4-7 1.923-.321 3.651.845 3.651 2.235A7.497 7.497 0 0 1 14 5.277c0-1.39 1.844-2.598 3.767-2.277 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.855-1.45-2.239-2.5"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/dollar-sign.js
var DollarSign = [
  ["line", { x1: "12", x2: "12", y1: "2", y2: "22" }],
  ["path", { d: "M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" }]
];

// node_modules/lucide/dist/esm/icons/donut.js
var Donut = [
  [
    "path",
    {
      d: "M20.5 10a2.5 2.5 0 0 1-2.4-3H18a2.95 2.95 0 0 1-2.6-4.4 10 10 0 1 0 6.3 7.1c-.3.2-.8.3-1.2.3"
    }
  ],
  ["circle", { cx: "12", cy: "12", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/door-closed.js
var DoorClosed = [
  ["path", { d: "M10 12h.01" }],
  ["path", { d: "M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14" }],
  ["path", { d: "M2 20h20" }]
];

// node_modules/lucide/dist/esm/icons/door-closed-locked.js
var DoorClosedLocked = [
  ["path", { d: "M10 12h.01" }],
  ["path", { d: "M18 9V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14" }],
  ["path", { d: "M2 20h8" }],
  ["path", { d: "M20 17v-2a2 2 0 1 0-4 0v2" }],
  ["rect", { x: "14", y: "17", width: "8", height: "5", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/door-open.js
var DoorOpen = [
  ["path", { d: "M11 20H2" }],
  [
    "path",
    {
      d: "M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z"
    }
  ],
  ["path", { d: "M11 4H8a2 2 0 0 0-2 2v14" }],
  ["path", { d: "M14 12h.01" }],
  ["path", { d: "M22 20h-3" }]
];

// node_modules/lucide/dist/esm/icons/dot.js
var Dot = [["circle", { cx: "12.1", cy: "12.1", r: "1" }]];

// node_modules/lucide/dist/esm/icons/download.js
var Download = [
  ["path", { d: "M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" }],
  ["polyline", { points: "7 10 12 15 17 10" }],
  ["line", { x1: "12", x2: "12", y1: "15", y2: "3" }]
];

// node_modules/lucide/dist/esm/icons/drafting-compass.js
var DraftingCompass = [
  ["path", { d: "m12.99 6.74 1.93 3.44" }],
  ["path", { d: "M19.136 12a10 10 0 0 1-14.271 0" }],
  ["path", { d: "m21 21-2.16-3.84" }],
  ["path", { d: "m3 21 8.02-14.26" }],
  ["circle", { cx: "12", cy: "5", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/dribbble.js
var Dribbble = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M19.13 5.09C15.22 9.14 10 10.44 2.25 10.94" }],
  ["path", { d: "M21.75 12.84c-6.62-1.41-12.14 1-16.38 6.32" }],
  ["path", { d: "M8.56 2.75c4.37 6 6 9.42 8 17.72" }]
];

// node_modules/lucide/dist/esm/icons/drama.js
var Drama = [
  ["path", { d: "M10 11h.01" }],
  ["path", { d: "M14 6h.01" }],
  ["path", { d: "M18 6h.01" }],
  ["path", { d: "M6.5 13.1h.01" }],
  ["path", { d: "M22 5c0 9-4 12-6 12s-6-3-6-12c0-2 2-3 6-3s6 1 6 3" }],
  ["path", { d: "M17.4 9.9c-.8.8-2 .8-2.8 0" }],
  [
    "path",
    {
      d: "M10.1 7.1C9 7.2 7.7 7.7 6 8.6c-3.5 2-4.7 3.9-3.7 5.6 4.5 7.8 9.5 8.4 11.2 7.4.9-.5 1.9-2.1 1.9-4.7"
    }
  ],
  ["path", { d: "M9.1 16.5c.3-1.1 1.4-1.7 2.4-1.4" }]
];

// node_modules/lucide/dist/esm/icons/drill.js
var Drill = [
  ["path", { d: "M10 18a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H5a3 3 0 0 1-3-3 1 1 0 0 1 1-1z" }],
  [
    "path",
    {
      d: "M13 10H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1l-.81 3.242a1 1 0 0 1-.97.758H8"
    }
  ],
  ["path", { d: "M14 4h3a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-3" }],
  ["path", { d: "M18 6h4" }],
  ["path", { d: "m5 10-2 8" }],
  ["path", { d: "m7 18 2-8" }]
];

// node_modules/lucide/dist/esm/icons/droplet-off.js
var DropletOff = [
  [
    "path",
    {
      d: "M18.715 13.186C18.29 11.858 17.384 10.607 16 9.5c-2-1.6-3.5-4-4-6.5a10.7 10.7 0 0 1-.884 2.586"
    }
  ],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M8.795 8.797A11 11 0 0 1 8 9.5C6 11.1 5 13 5 15a7 7 0 0 0 13.222 3.208" }]
];

// node_modules/lucide/dist/esm/icons/droplet.js
var Droplet = [
  [
    "path",
    {
      d: "M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/droplets.js
var Droplets = [
  [
    "path",
    {
      d: "M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"
    }
  ],
  [
    "path",
    {
      d: "M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/drum.js
var Drum = [
  ["path", { d: "m2 2 8 8" }],
  ["path", { d: "m22 2-8 8" }],
  ["ellipse", { cx: "12", cy: "9", rx: "10", ry: "5" }],
  ["path", { d: "M7 13.4v7.9" }],
  ["path", { d: "M12 14v8" }],
  ["path", { d: "M17 13.4v7.9" }],
  ["path", { d: "M2 9v8a10 5 0 0 0 20 0V9" }]
];

// node_modules/lucide/dist/esm/icons/drumstick.js
var Drumstick = [
  ["path", { d: "M15.4 15.63a7.875 6 135 1 1 6.23-6.23 4.5 3.43 135 0 0-6.23 6.23" }],
  ["path", { d: "m8.29 12.71-2.6 2.6a2.5 2.5 0 1 0-1.65 4.65A2.5 2.5 0 1 0 8.7 18.3l2.59-2.59" }]
];

// node_modules/lucide/dist/esm/icons/dumbbell.js
var Dumbbell = [
  [
    "path",
    {
      d: "M17.596 12.768a2 2 0 1 0 2.829-2.829l-1.768-1.767a2 2 0 0 0 2.828-2.829l-2.828-2.828a2 2 0 0 0-2.829 2.828l-1.767-1.768a2 2 0 1 0-2.829 2.829z"
    }
  ],
  ["path", { d: "m2.5 21.5 1.4-1.4" }],
  ["path", { d: "m20.1 3.9 1.4-1.4" }],
  [
    "path",
    {
      d: "M5.343 21.485a2 2 0 1 0 2.829-2.828l1.767 1.768a2 2 0 1 0 2.829-2.829l-6.364-6.364a2 2 0 1 0-2.829 2.829l1.768 1.767a2 2 0 0 0-2.828 2.829z"
    }
  ],
  ["path", { d: "m9.6 14.4 4.8-4.8" }]
];

// node_modules/lucide/dist/esm/icons/ear.js
var Ear = [
  ["path", { d: "M6 8.5a6.5 6.5 0 1 1 13 0c0 6-6 6-6 10a3.5 3.5 0 1 1-7 0" }],
  ["path", { d: "M15 8.5a2.5 2.5 0 0 0-5 0v1a2 2 0 1 1 0 4" }]
];

// node_modules/lucide/dist/esm/icons/ear-off.js
var EarOff = [
  ["path", { d: "M6 18.5a3.5 3.5 0 1 0 7 0c0-1.57.92-2.52 2.04-3.46" }],
  ["path", { d: "M6 8.5c0-.75.13-1.47.36-2.14" }],
  ["path", { d: "M8.8 3.15A6.5 6.5 0 0 1 19 8.5c0 1.63-.44 2.81-1.09 3.76" }],
  ["path", { d: "M12.5 6A2.5 2.5 0 0 1 15 8.5M10 13a2 2 0 0 0 1.82-1.18" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/earth-lock.js
var EarthLock = [
  ["path", { d: "M7 3.34V5a3 3 0 0 0 3 3" }],
  ["path", { d: "M11 21.95V18a2 2 0 0 0-2-2 2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05" }],
  ["path", { d: "M21.54 15H17a2 2 0 0 0-2 2v4.54" }],
  ["path", { d: "M12 2a10 10 0 1 0 9.54 13" }],
  ["path", { d: "M20 6V4a2 2 0 1 0-4 0v2" }],
  ["rect", { width: "8", height: "5", x: "14", y: "6", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/earth.js
var Earth = [
  ["path", { d: "M21.54 15H17a2 2 0 0 0-2 2v4.54" }],
  [
    "path",
    { d: "M7 3.34V5a3 3 0 0 0 3 3a2 2 0 0 1 2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2c0-1.1.9-2 2-2h3.17" }
  ],
  ["path", { d: "M11 21.95V18a2 2 0 0 0-2-2a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/eclipse.js
var Eclipse = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M12 2a7 7 0 1 0 10 10" }]
];

// node_modules/lucide/dist/esm/icons/egg-fried.js
var EggFried = [
  ["circle", { cx: "11.5", cy: "12.5", r: "3.5" }],
  [
    "path",
    {
      d: "M3 8c0-3.5 2.5-6 6.5-6 5 0 4.83 3 7.5 5s5 2 5 6c0 4.5-2.5 6.5-7 6.5-2.5 0-2.5 2.5-6 2.5s-7-2-7-5.5c0-3 1.5-3 1.5-5C3.5 10 3 9 3 8Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/egg-off.js
var EggOff = [
  [
    "path",
    {
      d: "M6.399 6.399C5.362 8.157 4.65 10.189 4.5 12c-.37 4.43 1.27 9.95 7.5 10 3.256-.026 5.259-1.547 6.375-3.625"
    }
  ],
  [
    "path",
    {
      d: "M19.532 13.875A14.07 14.07 0 0 0 19.5 12c-.36-4.34-3.95-9.96-7.5-10-1.04.012-2.082.502-3.046 1.297"
    }
  ],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/egg.js
var Egg = [
  [
    "path",
    {
      d: "M12 22c6.23-.05 7.87-5.57 7.5-10-.36-4.34-3.95-9.96-7.5-10-3.55.04-7.14 5.66-7.5 10-.37 4.43 1.27 9.95 7.5 10z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/ellipsis-vertical.js
var EllipsisVertical = [
  ["circle", { cx: "12", cy: "12", r: "1" }],
  ["circle", { cx: "12", cy: "5", r: "1" }],
  ["circle", { cx: "12", cy: "19", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/ellipsis.js
var Ellipsis = [
  ["circle", { cx: "12", cy: "12", r: "1" }],
  ["circle", { cx: "19", cy: "12", r: "1" }],
  ["circle", { cx: "5", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/equal-approximately.js
var EqualApproximately = [
  ["path", { d: "M5 15a6.5 6.5 0 0 1 7 0 6.5 6.5 0 0 0 7 0" }],
  ["path", { d: "M5 9a6.5 6.5 0 0 1 7 0 6.5 6.5 0 0 0 7 0" }]
];

// node_modules/lucide/dist/esm/icons/equal-not.js
var EqualNot = [
  ["line", { x1: "5", x2: "19", y1: "9", y2: "9" }],
  ["line", { x1: "5", x2: "19", y1: "15", y2: "15" }],
  ["line", { x1: "19", x2: "5", y1: "5", y2: "19" }]
];

// node_modules/lucide/dist/esm/icons/equal.js
var Equal = [
  ["line", { x1: "5", x2: "19", y1: "9", y2: "9" }],
  ["line", { x1: "5", x2: "19", y1: "15", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/eraser.js
var Eraser = [
  [
    "path",
    { d: "m7 21-4.3-4.3c-1-1-1-2.5 0-3.4l9.6-9.6c1-1 2.5-1 3.4 0l5.6 5.6c1 1 1 2.5 0 3.4L13 21" }
  ],
  ["path", { d: "M22 21H7" }],
  ["path", { d: "m5 11 9 9" }]
];

// node_modules/lucide/dist/esm/icons/ethernet-port.js
var EthernetPort = [
  [
    "path",
    { d: "m15 20 3-3h2a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h2l3 3z" }
  ],
  ["path", { d: "M6 8v1" }],
  ["path", { d: "M10 8v1" }],
  ["path", { d: "M14 8v1" }],
  ["path", { d: "M18 8v1" }]
];

// node_modules/lucide/dist/esm/icons/euro.js
var Euro = [
  ["path", { d: "M4 10h12" }],
  ["path", { d: "M4 14h9" }],
  [
    "path",
    { d: "M19 6a7.7 7.7 0 0 0-5.2-2A7.9 7.9 0 0 0 6 12c0 4.4 3.5 8 7.8 8 2 0 3.8-.8 5.2-2" }
  ]
];

// node_modules/lucide/dist/esm/icons/external-link.js
var ExternalLink = [
  ["path", { d: "M15 3h6v6" }],
  ["path", { d: "M10 14 21 3" }],
  ["path", { d: "M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" }]
];

// node_modules/lucide/dist/esm/icons/eye-closed.js
var EyeClosed = [
  ["path", { d: "m15 18-.722-3.25" }],
  ["path", { d: "M2 8a10.645 10.645 0 0 0 20 0" }],
  ["path", { d: "m20 15-1.726-2.05" }],
  ["path", { d: "m4 15 1.726-2.05" }],
  ["path", { d: "m9 18 .722-3.25" }]
];

// node_modules/lucide/dist/esm/icons/expand.js
var Expand = [
  ["path", { d: "m15 15 6 6" }],
  ["path", { d: "m15 9 6-6" }],
  ["path", { d: "M21 16v5h-5" }],
  ["path", { d: "M21 8V3h-5" }],
  ["path", { d: "M3 16v5h5" }],
  ["path", { d: "m3 21 6-6" }],
  ["path", { d: "M3 8V3h5" }],
  ["path", { d: "M9 9 3 3" }]
];

// node_modules/lucide/dist/esm/icons/eye-off.js
var EyeOff = [
  [
    "path",
    {
      d: "M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"
    }
  ],
  ["path", { d: "M14.084 14.158a3 3 0 0 1-4.242-4.242" }],
  [
    "path",
    {
      d: "M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"
    }
  ],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/facebook.js
var Facebook = [
  ["path", { d: "M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" }]
];

// node_modules/lucide/dist/esm/icons/eye.js
var Eye = [
  [
    "path",
    {
      d: "M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"
    }
  ],
  ["circle", { cx: "12", cy: "12", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/factory.js
var Factory = [
  [
    "path",
    { d: "M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" }
  ],
  ["path", { d: "M17 18h1" }],
  ["path", { d: "M12 18h1" }],
  ["path", { d: "M7 18h1" }]
];

// node_modules/lucide/dist/esm/icons/fan.js
var Fan = [
  [
    "path",
    {
      d: "M10.827 16.379a6.082 6.082 0 0 1-8.618-7.002l5.412 1.45a6.082 6.082 0 0 1 7.002-8.618l-1.45 5.412a6.082 6.082 0 0 1 8.618 7.002l-5.412-1.45a6.082 6.082 0 0 1-7.002 8.618l1.45-5.412Z"
    }
  ],
  ["path", { d: "M12 12v.01" }]
];

// node_modules/lucide/dist/esm/icons/fast-forward.js
var FastForward = [
  ["polygon", { points: "13 19 22 12 13 5 13 19" }],
  ["polygon", { points: "2 19 11 12 2 5 2 19" }]
];

// node_modules/lucide/dist/esm/icons/feather.js
var Feather = [
  [
    "path",
    {
      d: "M12.67 19a2 2 0 0 0 1.416-.588l6.154-6.172a6 6 0 0 0-8.49-8.49L5.586 9.914A2 2 0 0 0 5 11.328V18a1 1 0 0 0 1 1z"
    }
  ],
  ["path", { d: "M16 8 2 22" }],
  ["path", { d: "M17.5 15H9" }]
];

// node_modules/lucide/dist/esm/icons/fence.js
var Fence = [
  ["path", { d: "M4 3 2 5v15c0 .6.4 1 1 1h2c.6 0 1-.4 1-1V5Z" }],
  ["path", { d: "M6 8h4" }],
  ["path", { d: "M6 18h4" }],
  ["path", { d: "m12 3-2 2v15c0 .6.4 1 1 1h2c.6 0 1-.4 1-1V5Z" }],
  ["path", { d: "M14 8h4" }],
  ["path", { d: "M14 18h4" }],
  ["path", { d: "m20 3-2 2v15c0 .6.4 1 1 1h2c.6 0 1-.4 1-1V5Z" }]
];

// node_modules/lucide/dist/esm/icons/ferris-wheel.js
var FerrisWheel = [
  ["circle", { cx: "12", cy: "12", r: "2" }],
  ["path", { d: "M12 2v4" }],
  ["path", { d: "m6.8 15-3.5 2" }],
  ["path", { d: "m20.7 7-3.5 2" }],
  ["path", { d: "M6.8 9 3.3 7" }],
  ["path", { d: "m20.7 17-3.5-2" }],
  ["path", { d: "m9 22 3-8 3 8" }],
  ["path", { d: "M8 22h8" }],
  ["path", { d: "M18 18.7a9 9 0 1 0-12 0" }]
];

// node_modules/lucide/dist/esm/icons/figma.js
var Figma = [
  ["path", { d: "M5 5.5A3.5 3.5 0 0 1 8.5 2H12v7H8.5A3.5 3.5 0 0 1 5 5.5z" }],
  ["path", { d: "M12 2h3.5a3.5 3.5 0 1 1 0 7H12V2z" }],
  ["path", { d: "M12 12.5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 1 1-7 0z" }],
  ["path", { d: "M5 19.5A3.5 3.5 0 0 1 8.5 16H12v3.5a3.5 3.5 0 1 1-7 0z" }],
  ["path", { d: "M5 12.5A3.5 3.5 0 0 1 8.5 9H12v7H8.5A3.5 3.5 0 0 1 5 12.5z" }]
];

// node_modules/lucide/dist/esm/icons/file-archive.js
var FileArchive = [
  ["path", { d: "M10 12v-1" }],
  ["path", { d: "M10 18v-2" }],
  ["path", { d: "M10 7V6" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M15.5 22H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v16a2 2 0 0 0 .274 1.01" }],
  ["circle", { cx: "10", cy: "20", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/file-audio-2.js
var FileAudio2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v2" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["circle", { cx: "3", cy: "17", r: "1" }],
  ["path", { d: "M2 17v-3a4 4 0 0 1 8 0v3" }],
  ["circle", { cx: "9", cy: "17", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/file-audio.js
var FileAudio = [
  ["path", { d: "M17.5 22h.5a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  [
    "path",
    { d: "M2 19a2 2 0 1 1 4 0v1a2 2 0 1 1-4 0v-4a6 6 0 0 1 12 0v4a2 2 0 1 1-4 0v-1a2 2 0 1 1 4 0" }
  ]
];

// node_modules/lucide/dist/esm/icons/file-axis-3d.js
var FileAxis3d = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m8 18 4-4" }],
  ["path", { d: "M8 10v8h8" }]
];

// node_modules/lucide/dist/esm/icons/file-badge.js
var FileBadge = [
  ["path", { d: "M12 22h6a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M5 17a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" }],
  ["path", { d: "M7 16.5 8 22l-3-1-3 1 1-5.5" }]
];

// node_modules/lucide/dist/esm/icons/file-badge-2.js
var FileBadge2 = [
  [
    "path",
    {
      d: "m13.69 12.479 1.29 4.88a.5.5 0 0 1-.697.591l-1.844-.849a1 1 0 0 0-.88.001l-1.846.85a.5.5 0 0 1-.693-.593l1.29-4.88"
    }
  ],
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z" }],
  ["circle", { cx: "12", cy: "10", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/file-box.js
var FileBox = [
  ["path", { d: "M14.5 22H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  [
    "path",
    {
      d: "M3 13.1a2 2 0 0 0-1 1.76v3.24a2 2 0 0 0 .97 1.78L6 21.7a2 2 0 0 0 2.03.01L11 19.9a2 2 0 0 0 1-1.76V14.9a2 2 0 0 0-.97-1.78L8 11.3a2 2 0 0 0-2.03-.01Z"
    }
  ],
  ["path", { d: "M7 17v5" }],
  ["path", { d: "M11.7 14.2 7 17l-4.7-2.8" }]
];

// node_modules/lucide/dist/esm/icons/file-chart-column-increasing.js
var FileChartColumnIncreasing = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M8 18v-2" }],
  ["path", { d: "M12 18v-4" }],
  ["path", { d: "M16 18v-6" }]
];

// node_modules/lucide/dist/esm/icons/file-chart-column.js
var FileChartColumn = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M8 18v-1" }],
  ["path", { d: "M12 18v-6" }],
  ["path", { d: "M16 18v-3" }]
];

// node_modules/lucide/dist/esm/icons/file-chart-pie.js
var FileChartPie = [
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M16 22h2a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3.5" }],
  ["path", { d: "M4.017 11.512a6 6 0 1 0 8.466 8.475" }],
  [
    "path",
    {
      d: "M9 16a1 1 0 0 1-1-1v-4c0-.552.45-1.008.995-.917a6 6 0 0 1 4.922 4.922c.091.544-.365.995-.917.995z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/file-chart-line.js
var FileChartLine = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m16 13-3.5 3.5-2-2L8 17" }]
];

// node_modules/lucide/dist/esm/icons/file-check-2.js
var FileCheck2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m3 15 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/file-check.js
var FileCheck = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m9 15 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/file-clock.js
var FileClock = [
  ["path", { d: "M16 22h2a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["circle", { cx: "8", cy: "16", r: "6" }],
  ["path", { d: "M9.5 17.5 8 16.25V14" }]
];

// node_modules/lucide/dist/esm/icons/file-code-2.js
var FileCode2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m5 12-3 3 3 3" }],
  ["path", { d: "m9 18 3-3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/file-code.js
var FileCode = [
  ["path", { d: "M10 12.5 8 15l2 2.5" }],
  ["path", { d: "m14 12.5 2 2.5-2 2.5" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z" }]
];

// node_modules/lucide/dist/esm/icons/file-cog.js
var FileCog = [
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m2.305 15.53.923-.382" }],
  ["path", { d: "m3.228 12.852-.924-.383" }],
  ["path", { d: "M4.677 21.5a2 2 0 0 0 1.313.5H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v2.5" }],
  ["path", { d: "m4.852 11.228-.383-.923" }],
  ["path", { d: "m4.852 16.772-.383.924" }],
  ["path", { d: "m7.148 11.228.383-.923" }],
  ["path", { d: "m7.53 17.696-.382-.924" }],
  ["path", { d: "m8.772 12.852.923-.383" }],
  ["path", { d: "m8.772 15.148.923.383" }],
  ["circle", { cx: "6", cy: "14", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/file-diff.js
var FileDiff = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M9 10h6" }],
  ["path", { d: "M12 13V7" }],
  ["path", { d: "M9 17h6" }]
];

// node_modules/lucide/dist/esm/icons/file-digit.js
var FileDigit = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["rect", { width: "4", height: "6", x: "2", y: "12", rx: "2" }],
  ["path", { d: "M10 12h2v6" }],
  ["path", { d: "M10 18h4" }]
];

// node_modules/lucide/dist/esm/icons/file-down.js
var FileDown = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M12 18v-6" }],
  ["path", { d: "m9 15 3 3 3-3" }]
];

// node_modules/lucide/dist/esm/icons/file-heart.js
var FileHeart = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v2" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  [
    "path",
    {
      d: "M10.29 10.7a2.43 2.43 0 0 0-2.66-.52c-.29.12-.56.3-.78.53l-.35.34-.35-.34a2.43 2.43 0 0 0-2.65-.53c-.3.12-.56.3-.79.53-.95.94-1 2.53.2 3.74L6.5 18l3.6-3.55c1.2-1.21 1.14-2.8.19-3.74Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/file-image.js
var FileImage = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["circle", { cx: "10", cy: "12", r: "2" }],
  ["path", { d: "m20 17-1.296-1.296a2.41 2.41 0 0 0-3.408 0L9 22" }]
];

// node_modules/lucide/dist/esm/icons/file-input.js
var FileInput = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M2 15h10" }],
  ["path", { d: "m9 18 3-3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/file-json-2.js
var FileJson2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M4 12a1 1 0 0 0-1 1v1a1 1 0 0 1-1 1 1 1 0 0 1 1 1v1a1 1 0 0 0 1 1" }],
  ["path", { d: "M8 18a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1 1 1 0 0 1-1-1v-1a1 1 0 0 0-1-1" }]
];

// node_modules/lucide/dist/esm/icons/file-json.js
var FileJson = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M10 12a1 1 0 0 0-1 1v1a1 1 0 0 1-1 1 1 1 0 0 1 1 1v1a1 1 0 0 0 1 1" }],
  ["path", { d: "M14 18a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1 1 1 0 0 1-1-1v-1a1 1 0 0 0-1-1" }]
];

// node_modules/lucide/dist/esm/icons/file-key-2.js
var FileKey2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v6" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["circle", { cx: "4", cy: "16", r: "2" }],
  ["path", { d: "m10 10-4.5 4.5" }],
  ["path", { d: "m9 11 1 1" }]
];

// node_modules/lucide/dist/esm/icons/file-key.js
var FileKey = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["circle", { cx: "10", cy: "16", r: "2" }],
  ["path", { d: "m16 10-4.5 4.5" }],
  ["path", { d: "m15 11 1 1" }]
];

// node_modules/lucide/dist/esm/icons/file-lock-2.js
var FileLock2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v1" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["rect", { width: "8", height: "5", x: "2", y: "13", rx: "1" }],
  ["path", { d: "M8 13v-2a2 2 0 1 0-4 0v2" }]
];

// node_modules/lucide/dist/esm/icons/file-lock.js
var FileLock = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["rect", { width: "8", height: "6", x: "8", y: "12", rx: "1" }],
  ["path", { d: "M10 12v-2a2 2 0 1 1 4 0v2" }]
];

// node_modules/lucide/dist/esm/icons/file-minus-2.js
var FileMinus2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M3 15h6" }]
];

// node_modules/lucide/dist/esm/icons/file-minus.js
var FileMinus = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M9 15h6" }]
];

// node_modules/lucide/dist/esm/icons/file-music.js
var FileMusic = [
  ["path", { d: "M10.5 22H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v8.4" }],
  ["path", { d: "M8 18v-7.7L16 9v7" }],
  ["circle", { cx: "14", cy: "16", r: "2" }],
  ["circle", { cx: "6", cy: "18", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/file-output.js
var FileOutput = [
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M4 7V4a2 2 0 0 1 2-2 2 2 0 0 0-2 2" }],
  ["path", { d: "M4.063 20.999a2 2 0 0 0 2 1L18 22a2 2 0 0 0 2-2V7l-5-5H6" }],
  ["path", { d: "m5 11-3 3" }],
  ["path", { d: "m5 17-3-3h10" }]
];

// node_modules/lucide/dist/esm/icons/file-pen-line.js
var FilePenLine = [
  [
    "path",
    { d: "m18 5-2.414-2.414A2 2 0 0 0 14.172 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2" }
  ],
  [
    "path",
    {
      d: "M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ],
  ["path", { d: "M8 18h1" }]
];

// node_modules/lucide/dist/esm/icons/file-pen.js
var FilePen = [
  ["path", { d: "M12.5 22H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v9.5" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  [
    "path",
    {
      d: "M13.378 15.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/file-plus-2.js
var FilePlus2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M3 15h6" }],
  ["path", { d: "M6 12v6" }]
];

// node_modules/lucide/dist/esm/icons/file-plus.js
var FilePlus = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M9 15h6" }],
  ["path", { d: "M12 18v-6" }]
];

// node_modules/lucide/dist/esm/icons/file-question.js
var FileQuestion = [
  ["path", { d: "M12 17h.01" }],
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z" }],
  ["path", { d: "M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/file-scan.js
var FileScan = [
  ["path", { d: "M20 10V7l-5-5H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M16 14a2 2 0 0 0-2 2" }],
  ["path", { d: "M20 14a2 2 0 0 1 2 2" }],
  ["path", { d: "M20 22a2 2 0 0 0 2-2" }],
  ["path", { d: "M16 22a2 2 0 0 1-2-2" }]
];

// node_modules/lucide/dist/esm/icons/file-search-2.js
var FileSearch2 = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["circle", { cx: "11.5", cy: "14.5", r: "2.5" }],
  ["path", { d: "M13.3 16.3 15 18" }]
];

// node_modules/lucide/dist/esm/icons/file-sliders.js
var FileSliders = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "M10 11v2" }],
  ["path", { d: "M8 17h8" }],
  ["path", { d: "M14 16v2" }]
];

// node_modules/lucide/dist/esm/icons/file-search.js
var FileSearch = [
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M4.268 21a2 2 0 0 0 1.727 1H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3" }],
  ["path", { d: "m9 18-1.5-1.5" }],
  ["circle", { cx: "5", cy: "14", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/file-spreadsheet.js
var FileSpreadsheet = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M8 13h2" }],
  ["path", { d: "M14 13h2" }],
  ["path", { d: "M8 17h2" }],
  ["path", { d: "M14 17h2" }]
];

// node_modules/lucide/dist/esm/icons/file-stack.js
var FileStack = [
  ["path", { d: "M21 7h-3a2 2 0 0 1-2-2V2" }],
  [
    "path",
    { d: "M21 6v6.5c0 .8-.7 1.5-1.5 1.5h-7c-.8 0-1.5-.7-1.5-1.5v-9c0-.8.7-1.5 1.5-1.5H17Z" }
  ],
  ["path", { d: "M7 8v8.8c0 .3.2.6.4.8.2.2.5.4.8.4H15" }],
  ["path", { d: "M3 12v8.8c0 .3.2.6.4.8.2.2.5.4.8.4H11" }]
];

// node_modules/lucide/dist/esm/icons/file-symlink.js
var FileSymlink = [
  ["path", { d: "m10 18 3-3-3-3" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  [
    "path",
    { d: "M4 11V4a2 2 0 0 1 2-2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h7" }
  ]
];

// node_modules/lucide/dist/esm/icons/file-terminal.js
var FileTerminal = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m8 16 2-2-2-2" }],
  ["path", { d: "M12 18h4" }]
];

// node_modules/lucide/dist/esm/icons/file-text.js
var FileText = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M10 9H8" }],
  ["path", { d: "M16 13H8" }],
  ["path", { d: "M16 17H8" }]
];

// node_modules/lucide/dist/esm/icons/file-type-2.js
var FileType2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M2 13v-1h6v1" }],
  ["path", { d: "M5 12v6" }],
  ["path", { d: "M4 18h2" }]
];

// node_modules/lucide/dist/esm/icons/file-type.js
var FileType = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M9 13v-1h6v1" }],
  ["path", { d: "M12 12v6" }],
  ["path", { d: "M11 18h2" }]
];

// node_modules/lucide/dist/esm/icons/file-up.js
var FileUp = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M12 12v6" }],
  ["path", { d: "m15 15-3-3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/file-user.js
var FileUser = [
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M15 18a3 3 0 1 0-6 0" }],
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z" }],
  ["circle", { cx: "12", cy: "13", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/file-video-2.js
var FileVideo2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["rect", { width: "8", height: "6", x: "2", y: "12", rx: "1" }],
  ["path", { d: "m10 15.5 4 2.5v-6l-4 2.5" }]
];

// node_modules/lucide/dist/esm/icons/file-volume-2.js
var FileVolume2 = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M8 15h.01" }],
  ["path", { d: "M11.5 13.5a2.5 2.5 0 0 1 0 3" }],
  ["path", { d: "M15 12a5 5 0 0 1 0 6" }]
];

// node_modules/lucide/dist/esm/icons/file-video.js
var FileVideo = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m10 11 5 3-5 3v-6Z" }]
];

// node_modules/lucide/dist/esm/icons/file-warning.js
var FileWarning = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M12 9v4" }],
  ["path", { d: "M12 17h.01" }]
];

// node_modules/lucide/dist/esm/icons/file-volume.js
var FileVolume = [
  ["path", { d: "M11 11a5 5 0 0 1 0 6" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M4 6.765V4a2 2 0 0 1 2-2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-.93-.23" }],
  [
    "path",
    {
      d: "M7 10.51a.5.5 0 0 0-.826-.38l-1.893 1.628A1 1 0 0 1 3.63 12H2.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h1.129a1 1 0 0 1 .652.242l1.893 1.63a.5.5 0 0 0 .826-.38z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/file-x-2.js
var FileX2 = [
  ["path", { d: "M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m8 12.5-5 5" }],
  ["path", { d: "m3 12.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/file.js
var File = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }]
];

// node_modules/lucide/dist/esm/icons/file-x.js
var FileX = [
  ["path", { d: "M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "m14.5 12.5-5 5" }],
  ["path", { d: "m9.5 12.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/files.js
var Files = [
  ["path", { d: "M20 7h-3a2 2 0 0 1-2-2V2" }],
  ["path", { d: "M9 18a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h7l4 4v10a2 2 0 0 1-2 2Z" }],
  ["path", { d: "M3 7.6v12.8A1.6 1.6 0 0 0 4.6 22h9.8" }]
];

// node_modules/lucide/dist/esm/icons/film.js
var Film = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 3v18" }],
  ["path", { d: "M3 7.5h4" }],
  ["path", { d: "M3 12h18" }],
  ["path", { d: "M3 16.5h4" }],
  ["path", { d: "M17 3v18" }],
  ["path", { d: "M17 7.5h4" }],
  ["path", { d: "M17 16.5h4" }]
];

// node_modules/lucide/dist/esm/icons/fire-extinguisher.js
var FireExtinguisher = [
  ["path", { d: "M15 6.5V3a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v3.5" }],
  ["path", { d: "M9 18h8" }],
  ["path", { d: "M18 3h-3" }],
  ["path", { d: "M11 3a6 6 0 0 0-6 6v11" }],
  ["path", { d: "M5 13h4" }],
  ["path", { d: "M17 10a4 4 0 0 0-8 0v10a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2Z" }]
];

// node_modules/lucide/dist/esm/icons/fingerprint.js
var Fingerprint = [
  ["path", { d: "M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4" }],
  ["path", { d: "M14 13.12c0 2.38 0 6.38-1 8.88" }],
  ["path", { d: "M17.29 21.02c.12-.6.43-2.3.5-3.02" }],
  ["path", { d: "M2 12a10 10 0 0 1 18-6" }],
  ["path", { d: "M2 16h.01" }],
  ["path", { d: "M21.8 16c.2-2 .131-5.354 0-6" }],
  ["path", { d: "M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2" }],
  ["path", { d: "M8.65 22c.21-.66.45-1.32.57-2" }],
  ["path", { d: "M9 6.8a6 6 0 0 1 9 5.2v2" }]
];

// node_modules/lucide/dist/esm/icons/fish-off.js
var FishOff = [
  [
    "path",
    {
      d: "M18 12.47v.03m0-.5v.47m-.475 5.056A6.744 6.744 0 0 1 15 18c-3.56 0-7.56-2.53-8.5-6 .348-1.28 1.114-2.433 2.121-3.38m3.444-2.088A8.802 8.802 0 0 1 15 6c3.56 0 6.06 2.54 7 6-.309 1.14-.786 2.177-1.413 3.058"
    }
  ],
  [
    "path",
    {
      d: "M7 10.67C7 8 5.58 5.97 2.73 5.5c-1 1.5-1 5 .23 6.5-1.24 1.5-1.24 5-.23 6.5C5.58 18.03 7 16 7 13.33m7.48-4.372A9.77 9.77 0 0 1 16 6.07m0 11.86a9.77 9.77 0 0 1-1.728-3.618"
    }
  ],
  [
    "path",
    {
      d: "m16.01 17.93-.23 1.4A2 2 0 0 1 13.8 21H9.5a5.96 5.96 0 0 0 1.49-3.98M8.53 3h5.27a2 2 0 0 1 1.98 1.67l.23 1.4M2 2l20 20"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/fish-symbol.js
var FishSymbol = [["path", { d: "M2 16s9-15 20-4C11 23 2 8 2 8" }]];

// node_modules/lucide/dist/esm/icons/fish.js
var Fish = [
  [
    "path",
    {
      d: "M6.5 12c.94-3.46 4.94-6 8.5-6 3.56 0 6.06 2.54 7 6-.94 3.47-3.44 6-7 6s-7.56-2.53-8.5-6Z"
    }
  ],
  ["path", { d: "M18 12v.5" }],
  ["path", { d: "M16 17.93a9.77 9.77 0 0 1 0-11.86" }],
  [
    "path",
    {
      d: "M7 10.67C7 8 5.58 5.97 2.73 5.5c-1 1.5-1 5 .23 6.5-1.24 1.5-1.24 5-.23 6.5C5.58 18.03 7 16 7 13.33"
    }
  ],
  ["path", { d: "M10.46 7.26C10.2 5.88 9.17 4.24 8 3h5.8a2 2 0 0 1 1.98 1.67l.23 1.4" }],
  ["path", { d: "m16.01 17.93-.23 1.4A2 2 0 0 1 13.8 21H9.5a5.96 5.96 0 0 0 1.49-3.98" }]
];

// node_modules/lucide/dist/esm/icons/flag-off.js
var FlagOff = [
  ["path", { d: "M8 2c3 0 5 2 8 2s4-1 4-1v11" }],
  ["path", { d: "M4 22V4" }],
  ["path", { d: "M4 15s1-1 4-1 5 2 8 2" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/flag-triangle-left.js
var FlagTriangleLeft = [["path", { d: "M17 22V2L7 7l10 5" }]];

// node_modules/lucide/dist/esm/icons/flag-triangle-right.js
var FlagTriangleRight = [["path", { d: "M7 22V2l10 5-10 5" }]];

// node_modules/lucide/dist/esm/icons/flag.js
var Flag = [
  ["path", { d: "M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z" }],
  ["line", { x1: "4", x2: "4", y1: "22", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/flame-kindling.js
var FlameKindling = [
  [
    "path",
    {
      d: "M12 2c1 3 2.5 3.5 3.5 4.5A5 5 0 0 1 17 10a5 5 0 1 1-10 0c0-.3 0-.6.1-.9a2 2 0 1 0 3.3-2C8 4.5 11 2 12 2Z"
    }
  ],
  ["path", { d: "m5 22 14-4" }],
  ["path", { d: "m5 18 14 4" }]
];

// node_modules/lucide/dist/esm/icons/flame.js
var Flame = [
  [
    "path",
    {
      d: "M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/flashlight-off.js
var FlashlightOff = [
  ["path", { d: "M16 16v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V10c0-2-2-2-2-4" }],
  ["path", { d: "M7 2h11v4c0 2-2 2-2 4v1" }],
  ["line", { x1: "11", x2: "18", y1: "6", y2: "6" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/flashlight.js
var Flashlight = [
  ["path", { d: "M18 6c0 2-2 2-2 4v10a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V10c0-2-2-2-2-4V2h12z" }],
  ["line", { x1: "6", x2: "18", y1: "6", y2: "6" }],
  ["line", { x1: "12", x2: "12", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/flask-conical-off.js
var FlaskConicalOff = [
  ["path", { d: "M10 2v2.343" }],
  ["path", { d: "M14 2v6.343" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M20 20a2 2 0 0 1-2 2H6a2 2 0 0 1-1.755-2.96l5.227-9.563" }],
  ["path", { d: "M6.453 15H15" }],
  ["path", { d: "M8.5 2h7" }]
];

// node_modules/lucide/dist/esm/icons/flask-conical.js
var FlaskConical = [
  [
    "path",
    {
      d: "M14 2v6a2 2 0 0 0 .245.96l5.51 10.08A2 2 0 0 1 18 22H6a2 2 0 0 1-1.755-2.96l5.51-10.08A2 2 0 0 0 10 8V2"
    }
  ],
  ["path", { d: "M6.453 15h11.094" }],
  ["path", { d: "M8.5 2h7" }]
];

// node_modules/lucide/dist/esm/icons/flask-round.js
var FlaskRound = [
  ["path", { d: "M10 2v6.292a7 7 0 1 0 4 0V2" }],
  ["path", { d: "M5 15h14" }],
  ["path", { d: "M8.5 2h7" }]
];

// node_modules/lucide/dist/esm/icons/flip-horizontal-2.js
var FlipHorizontal2 = [
  ["path", { d: "m3 7 5 5-5 5V7" }],
  ["path", { d: "m21 7-5 5 5 5V7" }],
  ["path", { d: "M12 20v2" }],
  ["path", { d: "M12 14v2" }],
  ["path", { d: "M12 8v2" }],
  ["path", { d: "M12 2v2" }]
];

// node_modules/lucide/dist/esm/icons/flip-horizontal.js
var FlipHorizontal = [
  ["path", { d: "M8 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h3" }],
  ["path", { d: "M16 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3" }],
  ["path", { d: "M12 20v2" }],
  ["path", { d: "M12 14v2" }],
  ["path", { d: "M12 8v2" }],
  ["path", { d: "M12 2v2" }]
];

// node_modules/lucide/dist/esm/icons/flip-vertical-2.js
var FlipVertical2 = [
  ["path", { d: "m17 3-5 5-5-5h10" }],
  ["path", { d: "m17 21-5-5-5 5h10" }],
  ["path", { d: "M4 12H2" }],
  ["path", { d: "M10 12H8" }],
  ["path", { d: "M16 12h-2" }],
  ["path", { d: "M22 12h-2" }]
];

// node_modules/lucide/dist/esm/icons/flip-vertical.js
var FlipVertical = [
  ["path", { d: "M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3" }],
  ["path", { d: "M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3" }],
  ["path", { d: "M4 12H2" }],
  ["path", { d: "M10 12H8" }],
  ["path", { d: "M16 12h-2" }],
  ["path", { d: "M22 12h-2" }]
];

// node_modules/lucide/dist/esm/icons/flower-2.js
var Flower2 = [
  [
    "path",
    {
      d: "M12 5a3 3 0 1 1 3 3m-3-3a3 3 0 1 0-3 3m3-3v1M9 8a3 3 0 1 0 3 3M9 8h1m5 0a3 3 0 1 1-3 3m3-3h-1m-2 3v-1"
    }
  ],
  ["circle", { cx: "12", cy: "8", r: "2" }],
  ["path", { d: "M12 10v12" }],
  ["path", { d: "M12 22c4.2 0 7-1.667 7-5-4.2 0-7 1.667-7 5Z" }],
  ["path", { d: "M12 22c-4.2 0-7-1.667-7-5 4.2 0 7 1.667 7 5Z" }]
];

// node_modules/lucide/dist/esm/icons/focus.js
var Focus = [
  ["circle", { cx: "12", cy: "12", r: "3" }],
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }]
];

// node_modules/lucide/dist/esm/icons/flower.js
var Flower = [
  ["circle", { cx: "12", cy: "12", r: "3" }],
  [
    "path",
    {
      d: "M12 16.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 1 1 12 7.5a4.5 4.5 0 1 1 4.5 4.5 4.5 4.5 0 1 1-4.5 4.5"
    }
  ],
  ["path", { d: "M12 7.5V9" }],
  ["path", { d: "M7.5 12H9" }],
  ["path", { d: "M16.5 12H15" }],
  ["path", { d: "M12 16.5V15" }],
  ["path", { d: "m8 8 1.88 1.88" }],
  ["path", { d: "M14.12 9.88 16 8" }],
  ["path", { d: "m8 16 1.88-1.88" }],
  ["path", { d: "M14.12 14.12 16 16" }]
];

// node_modules/lucide/dist/esm/icons/fold-horizontal.js
var FoldHorizontal = [
  ["path", { d: "M2 12h6" }],
  ["path", { d: "M22 12h-6" }],
  ["path", { d: "M12 2v2" }],
  ["path", { d: "M12 8v2" }],
  ["path", { d: "M12 14v2" }],
  ["path", { d: "M12 20v2" }],
  ["path", { d: "m19 9-3 3 3 3" }],
  ["path", { d: "m5 15 3-3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/folder-archive.js
var FolderArchive = [
  ["circle", { cx: "15", cy: "19", r: "2" }],
  [
    "path",
    {
      d: "M20.9 19.8A2 2 0 0 0 22 18V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h5.1"
    }
  ],
  ["path", { d: "M15 11v-1" }],
  ["path", { d: "M15 17v-2" }]
];

// node_modules/lucide/dist/esm/icons/fold-vertical.js
var FoldVertical = [
  ["path", { d: "M12 22v-6" }],
  ["path", { d: "M12 8V2" }],
  ["path", { d: "M4 12H2" }],
  ["path", { d: "M10 12H8" }],
  ["path", { d: "M16 12h-2" }],
  ["path", { d: "M22 12h-2" }],
  ["path", { d: "m15 19-3-3-3 3" }],
  ["path", { d: "m15 5-3 3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/folder-check.js
var FolderCheck = [
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "m9 13 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/folder-clock.js
var FolderClock = [
  ["circle", { cx: "16", cy: "16", r: "6" }],
  [
    "path",
    {
      d: "M7 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2"
    }
  ],
  ["path", { d: "M16 14v2l1 1" }]
];

// node_modules/lucide/dist/esm/icons/folder-closed.js
var FolderClosed = [
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "M2 10h20" }]
];

// node_modules/lucide/dist/esm/icons/folder-code.js
var FolderCode = [
  ["path", { d: "M10 10.5 8 13l2 2.5" }],
  ["path", { d: "m14 10.5 2 2.5-2 2.5" }],
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folder-cog.js
var FolderCog = [
  [
    "path",
    {
      d: "M10.3 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.98a2 2 0 0 1 1.69.9l.66 1.2A2 2 0 0 0 12 6h8a2 2 0 0 1 2 2v3.3"
    }
  ],
  ["path", { d: "m14.305 19.53.923-.382" }],
  ["path", { d: "m15.228 16.852-.923-.383" }],
  ["path", { d: "m16.852 15.228-.383-.923" }],
  ["path", { d: "m16.852 20.772-.383.924" }],
  ["path", { d: "m19.148 15.228.383-.923" }],
  ["path", { d: "m19.53 21.696-.382-.924" }],
  ["path", { d: "m20.772 16.852.924-.383" }],
  ["path", { d: "m20.772 19.148.924.383" }],
  ["circle", { cx: "18", cy: "18", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/folder-dot.js
var FolderDot = [
  [
    "path",
    {
      d: "M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"
    }
  ],
  ["circle", { cx: "12", cy: "13", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/folder-down.js
var FolderDown = [
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "M12 10v6" }],
  ["path", { d: "m15 13-3 3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/folder-git.js
var FolderGit = [
  ["circle", { cx: "12", cy: "13", r: "2" }],
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "M14 13h3" }],
  ["path", { d: "M7 13h3" }]
];

// node_modules/lucide/dist/esm/icons/folder-heart.js
var FolderHeart = [
  [
    "path",
    {
      d: "M11 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v1.5"
    }
  ],
  [
    "path",
    {
      d: "M13.9 17.45c-1.2-1.2-1.14-2.8-.2-3.73a2.43 2.43 0 0 1 3.44 0l.36.34.34-.34a2.43 2.43 0 0 1 3.45-.01c.95.95 1 2.53-.2 3.74L17.5 21Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folder-git-2.js
var FolderGit2 = [
  [
    "path",
    {
      d: "M9 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v5"
    }
  ],
  ["circle", { cx: "13", cy: "12", r: "2" }],
  ["path", { d: "M18 19c-2.8 0-5-2.2-5-5v8" }],
  ["circle", { cx: "20", cy: "19", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/folder-input.js
var FolderInput = [
  [
    "path",
    {
      d: "M2 9V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-1"
    }
  ],
  ["path", { d: "M2 13h10" }],
  ["path", { d: "m9 16 3-3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/folder-kanban.js
var FolderKanban = [
  [
    "path",
    {
      d: "M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"
    }
  ],
  ["path", { d: "M8 10v4" }],
  ["path", { d: "M12 10v2" }],
  ["path", { d: "M16 10v6" }]
];

// node_modules/lucide/dist/esm/icons/folder-key.js
var FolderKey = [
  ["circle", { cx: "16", cy: "20", r: "2" }],
  [
    "path",
    {
      d: "M10 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v2"
    }
  ],
  ["path", { d: "m22 14-4.5 4.5" }],
  ["path", { d: "m21 15 1 1" }]
];

// node_modules/lucide/dist/esm/icons/folder-lock.js
var FolderLock = [
  ["rect", { width: "8", height: "5", x: "14", y: "17", rx: "1" }],
  [
    "path",
    {
      d: "M10 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v2.5"
    }
  ],
  ["path", { d: "M20 17v-2a2 2 0 1 0-4 0v2" }]
];

// node_modules/lucide/dist/esm/icons/folder-minus.js
var FolderMinus = [
  ["path", { d: "M9 13h6" }],
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folder-open-dot.js
var FolderOpenDot = [
  [
    "path",
    {
      d: "m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"
    }
  ],
  ["circle", { cx: "14", cy: "15", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/folder-open.js
var FolderOpen = [
  [
    "path",
    {
      d: "m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folder-pen.js
var FolderPen = [
  [
    "path",
    {
      d: "M2 11.5V5a2 2 0 0 1 2-2h3.9c.7 0 1.3.3 1.7.9l.8 1.2c.4.6 1 .9 1.7.9H20a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-9.5"
    }
  ],
  [
    "path",
    {
      d: "M11.378 13.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folder-plus.js
var FolderPlus = [
  ["path", { d: "M12 10v6" }],
  ["path", { d: "M9 13h6" }],
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folder-output.js
var FolderOutput = [
  [
    "path",
    {
      d: "M2 7.5V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-1.5"
    }
  ],
  ["path", { d: "M2 13h10" }],
  ["path", { d: "m5 10-3 3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/folder-root.js
var FolderRoot = [
  [
    "path",
    {
      d: "M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"
    }
  ],
  ["circle", { cx: "12", cy: "13", r: "2" }],
  ["path", { d: "M12 15v5" }]
];

// node_modules/lucide/dist/esm/icons/folder-search.js
var FolderSearch = [
  [
    "path",
    {
      d: "M10.7 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v4.1"
    }
  ],
  ["path", { d: "m21 21-1.9-1.9" }],
  ["circle", { cx: "17", cy: "17", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/folder-search-2.js
var FolderSearch2 = [
  ["circle", { cx: "11.5", cy: "12.5", r: "2.5" }],
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "M13.3 14.3 15 16" }]
];

// node_modules/lucide/dist/esm/icons/folder-symlink.js
var FolderSymlink = [
  [
    "path",
    {
      d: "M2 9V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h7"
    }
  ],
  ["path", { d: "m8 16 3-3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/folder-tree.js
var FolderTree = [
  [
    "path",
    {
      d: "M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z"
    }
  ],
  [
    "path",
    {
      d: "M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z"
    }
  ],
  ["path", { d: "M3 5a2 2 0 0 0 2 2h3" }],
  ["path", { d: "M3 3v13a2 2 0 0 0 2 2h3" }]
];

// node_modules/lucide/dist/esm/icons/folder-sync.js
var FolderSync = [
  [
    "path",
    {
      d: "M9 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2v.5"
    }
  ],
  ["path", { d: "M12 10v4h4" }],
  ["path", { d: "m12 14 1.535-1.605a5 5 0 0 1 8 1.5" }],
  ["path", { d: "M22 22v-4h-4" }],
  ["path", { d: "m22 18-1.535 1.605a5 5 0 0 1-8-1.5" }]
];

// node_modules/lucide/dist/esm/icons/folder-up.js
var FolderUp = [
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "M12 10v6" }],
  ["path", { d: "m9 13 3-3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/folder-x.js
var FolderX = [
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "m9.5 10.5 5 5" }],
  ["path", { d: "m14.5 10.5-5 5" }]
];

// node_modules/lucide/dist/esm/icons/folder.js
var Folder = [
  [
    "path",
    {
      d: "M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/folders.js
var Folders = [
  [
    "path",
    {
      d: "M20 17a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3.9a2 2 0 0 1-1.69-.9l-.81-1.2a2 2 0 0 0-1.67-.9H8a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2Z"
    }
  ],
  ["path", { d: "M2 8v11a2 2 0 0 0 2 2h14" }]
];

// node_modules/lucide/dist/esm/icons/footprints.js
var Footprints = [
  [
    "path",
    {
      d: "M4 16v-2.38C4 11.5 2.97 10.5 3 8c.03-2.72 1.49-6 4.5-6C9.37 2 10 3.8 10 5.5c0 3.11-2 5.66-2 8.68V16a2 2 0 1 1-4 0Z"
    }
  ],
  [
    "path",
    {
      d: "M20 20v-2.38c0-2.12 1.03-3.12 1-5.62-.03-2.72-1.49-6-4.5-6C14.63 6 14 7.8 14 9.5c0 3.11 2 5.66 2 8.68V20a2 2 0 1 0 4 0Z"
    }
  ],
  ["path", { d: "M16 17h4" }],
  ["path", { d: "M4 13h4" }]
];

// node_modules/lucide/dist/esm/icons/forklift.js
var Forklift = [
  ["path", { d: "M12 12H5a2 2 0 0 0-2 2v5" }],
  ["circle", { cx: "13", cy: "19", r: "2" }],
  ["circle", { cx: "5", cy: "19", r: "2" }],
  ["path", { d: "M8 19h3m5-17v17h6M6 12V7c0-1.1.9-2 2-2h3l5 5" }]
];

// node_modules/lucide/dist/esm/icons/forward.js
var Forward = [
  ["polyline", { points: "15 17 20 12 15 7" }],
  ["path", { d: "M4 18v-2a4 4 0 0 1 4-4h12" }]
];

// node_modules/lucide/dist/esm/icons/frame.js
var Frame = [
  ["line", { x1: "22", x2: "2", y1: "6", y2: "6" }],
  ["line", { x1: "22", x2: "2", y1: "18", y2: "18" }],
  ["line", { x1: "6", x2: "6", y1: "2", y2: "22" }],
  ["line", { x1: "18", x2: "18", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/framer.js
var Framer = [["path", { d: "M5 16V9h14V2H5l14 14h-7m-7 0 7 7v-7m-7 0h7" }]];

// node_modules/lucide/dist/esm/icons/frown.js
var Frown = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M16 16s-1.5-2-4-2-4 2-4 2" }],
  ["line", { x1: "9", x2: "9.01", y1: "9", y2: "9" }],
  ["line", { x1: "15", x2: "15.01", y1: "9", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/fuel.js
var Fuel = [
  ["line", { x1: "3", x2: "15", y1: "22", y2: "22" }],
  ["line", { x1: "4", x2: "14", y1: "9", y2: "9" }],
  ["path", { d: "M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18" }],
  [
    "path",
    { d: "M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5" }
  ]
];

// node_modules/lucide/dist/esm/icons/fullscreen.js
var Fullscreen = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["rect", { width: "10", height: "8", x: "7", y: "8", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/funnel-plus.js
var FunnelPlus = [
  [
    "path",
    {
      d: "M13.354 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14v6a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341l1.218-1.348"
    }
  ],
  ["path", { d: "M16 6h6" }],
  ["path", { d: "M19 3v6" }]
];

// node_modules/lucide/dist/esm/icons/funnel-x.js
var FunnelX = [
  [
    "path",
    {
      d: "M12.531 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14v6a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341l.427-.473"
    }
  ],
  ["path", { d: "m16.5 3.5 5 5" }],
  ["path", { d: "m21.5 3.5-5 5" }]
];

// node_modules/lucide/dist/esm/icons/funnel.js
var Funnel = [
  [
    "path",
    {
      d: "M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/gallery-horizontal-end.js
var GalleryHorizontalEnd = [
  ["path", { d: "M2 7v10" }],
  ["path", { d: "M6 5v14" }],
  ["rect", { width: "12", height: "18", x: "10", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/gallery-horizontal.js
var GalleryHorizontal = [
  ["path", { d: "M2 3v18" }],
  ["rect", { width: "12", height: "18", x: "6", y: "3", rx: "2" }],
  ["path", { d: "M22 3v18" }]
];

// node_modules/lucide/dist/esm/icons/gallery-thumbnails.js
var GalleryThumbnails = [
  ["rect", { width: "18", height: "14", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M4 21h1" }],
  ["path", { d: "M9 21h1" }],
  ["path", { d: "M14 21h1" }],
  ["path", { d: "M19 21h1" }]
];

// node_modules/lucide/dist/esm/icons/gallery-vertical-end.js
var GalleryVerticalEnd = [
  ["path", { d: "M7 2h10" }],
  ["path", { d: "M5 6h14" }],
  ["rect", { width: "18", height: "12", x: "3", y: "10", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/gallery-vertical.js
var GalleryVertical = [
  ["path", { d: "M3 2h18" }],
  ["rect", { width: "18", height: "12", x: "3", y: "6", rx: "2" }],
  ["path", { d: "M3 22h18" }]
];

// node_modules/lucide/dist/esm/icons/gamepad-2.js
var Gamepad2 = [
  ["line", { x1: "6", x2: "10", y1: "11", y2: "11" }],
  ["line", { x1: "8", x2: "8", y1: "9", y2: "13" }],
  ["line", { x1: "15", x2: "15.01", y1: "12", y2: "12" }],
  ["line", { x1: "18", x2: "18.01", y1: "10", y2: "10" }],
  [
    "path",
    {
      d: "M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 0 1 9.828 16h4.344a2 2 0 0 1 1.414.586L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.545-.604-6.584-.685-7.258-.007-.05-.011-.1-.017-.151A4 4 0 0 0 17.32 5z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/gauge.js
var Gauge = [
  ["path", { d: "m12 14 4-4" }],
  ["path", { d: "M3.34 19a10 10 0 1 1 17.32 0" }]
];

// node_modules/lucide/dist/esm/icons/gamepad.js
var Gamepad = [
  ["line", { x1: "6", x2: "10", y1: "12", y2: "12" }],
  ["line", { x1: "8", x2: "8", y1: "10", y2: "14" }],
  ["line", { x1: "15", x2: "15.01", y1: "13", y2: "13" }],
  ["line", { x1: "18", x2: "18.01", y1: "11", y2: "11" }],
  ["rect", { width: "20", height: "12", x: "2", y: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/gavel.js
var Gavel = [
  ["path", { d: "m14.5 12.5-8 8a2.119 2.119 0 1 1-3-3l8-8" }],
  ["path", { d: "m16 16 6-6" }],
  ["path", { d: "m8 8 6-6" }],
  ["path", { d: "m9 7 8 8" }],
  ["path", { d: "m21 11-8-8" }]
];

// node_modules/lucide/dist/esm/icons/gem.js
var Gem = [
  ["path", { d: "M6 3h12l4 6-10 13L2 9Z" }],
  ["path", { d: "M11 3 8 9l4 13 4-13-3-6" }],
  ["path", { d: "M2 9h20" }]
];

// node_modules/lucide/dist/esm/icons/ghost.js
var Ghost = [
  ["path", { d: "M9 10h.01" }],
  ["path", { d: "M15 10h.01" }],
  ["path", { d: "M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" }]
];

// node_modules/lucide/dist/esm/icons/gift.js
var Gift = [
  ["rect", { x: "3", y: "8", width: "18", height: "4", rx: "1" }],
  ["path", { d: "M12 8v13" }],
  ["path", { d: "M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" }],
  ["path", { d: "M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" }]
];

// node_modules/lucide/dist/esm/icons/git-branch-plus.js
var GitBranchPlus = [
  ["path", { d: "M6 3v12" }],
  ["path", { d: "M18 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" }],
  ["path", { d: "M6 21a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" }],
  ["path", { d: "M15 6a9 9 0 0 0-9 9" }],
  ["path", { d: "M18 15v6" }],
  ["path", { d: "M21 18h-6" }]
];

// node_modules/lucide/dist/esm/icons/git-branch.js
var GitBranch = [
  ["line", { x1: "6", x2: "6", y1: "3", y2: "15" }],
  ["circle", { cx: "18", cy: "6", r: "3" }],
  ["circle", { cx: "6", cy: "18", r: "3" }],
  ["path", { d: "M18 9a9 9 0 0 1-9 9" }]
];

// node_modules/lucide/dist/esm/icons/git-commit-horizontal.js
var GitCommitHorizontal = [
  ["circle", { cx: "12", cy: "12", r: "3" }],
  ["line", { x1: "3", x2: "9", y1: "12", y2: "12" }],
  ["line", { x1: "15", x2: "21", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/git-commit-vertical.js
var GitCommitVertical = [
  ["path", { d: "M12 3v6" }],
  ["circle", { cx: "12", cy: "12", r: "3" }],
  ["path", { d: "M12 15v6" }]
];

// node_modules/lucide/dist/esm/icons/git-compare.js
var GitCompare = [
  ["circle", { cx: "18", cy: "18", r: "3" }],
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M13 6h3a2 2 0 0 1 2 2v7" }],
  ["path", { d: "M11 18H8a2 2 0 0 1-2-2V9" }]
];

// node_modules/lucide/dist/esm/icons/git-compare-arrows.js
var GitCompareArrows = [
  ["circle", { cx: "5", cy: "6", r: "3" }],
  ["path", { d: "M12 6h5a2 2 0 0 1 2 2v7" }],
  ["path", { d: "m15 9-3-3 3-3" }],
  ["circle", { cx: "19", cy: "18", r: "3" }],
  ["path", { d: "M12 18H7a2 2 0 0 1-2-2V9" }],
  ["path", { d: "m9 15 3 3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/git-fork.js
var GitFork = [
  ["circle", { cx: "12", cy: "18", r: "3" }],
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["circle", { cx: "18", cy: "6", r: "3" }],
  ["path", { d: "M18 9v2c0 .6-.4 1-1 1H7c-.6 0-1-.4-1-1V9" }],
  ["path", { d: "M12 12v3" }]
];

// node_modules/lucide/dist/esm/icons/git-graph.js
var GitGraph = [
  ["circle", { cx: "5", cy: "6", r: "3" }],
  ["path", { d: "M5 9v6" }],
  ["circle", { cx: "5", cy: "18", r: "3" }],
  ["path", { d: "M12 3v18" }],
  ["circle", { cx: "19", cy: "6", r: "3" }],
  ["path", { d: "M16 15.7A9 9 0 0 0 19 9" }]
];

// node_modules/lucide/dist/esm/icons/git-merge.js
var GitMerge = [
  ["circle", { cx: "18", cy: "18", r: "3" }],
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M6 21V9a9 9 0 0 0 9 9" }]
];

// node_modules/lucide/dist/esm/icons/git-pull-request-arrow.js
var GitPullRequestArrow = [
  ["circle", { cx: "5", cy: "6", r: "3" }],
  ["path", { d: "M5 9v12" }],
  ["circle", { cx: "19", cy: "18", r: "3" }],
  ["path", { d: "m15 9-3-3 3-3" }],
  ["path", { d: "M12 6h5a2 2 0 0 1 2 2v7" }]
];

// node_modules/lucide/dist/esm/icons/git-pull-request-closed.js
var GitPullRequestClosed = [
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M6 9v12" }],
  ["path", { d: "m21 3-6 6" }],
  ["path", { d: "m21 9-6-6" }],
  ["path", { d: "M18 11.5V15" }],
  ["circle", { cx: "18", cy: "18", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/git-pull-request-create-arrow.js
var GitPullRequestCreateArrow = [
  ["circle", { cx: "5", cy: "6", r: "3" }],
  ["path", { d: "M5 9v12" }],
  ["path", { d: "m15 9-3-3 3-3" }],
  ["path", { d: "M12 6h5a2 2 0 0 1 2 2v3" }],
  ["path", { d: "M19 15v6" }],
  ["path", { d: "M22 18h-6" }]
];

// node_modules/lucide/dist/esm/icons/git-pull-request-create.js
var GitPullRequestCreate = [
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M6 9v12" }],
  ["path", { d: "M13 6h3a2 2 0 0 1 2 2v3" }],
  ["path", { d: "M18 15v6" }],
  ["path", { d: "M21 18h-6" }]
];

// node_modules/lucide/dist/esm/icons/git-pull-request-draft.js
var GitPullRequestDraft = [
  ["circle", { cx: "18", cy: "18", r: "3" }],
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M18 6V5" }],
  ["path", { d: "M18 11v-1" }],
  ["line", { x1: "6", x2: "6", y1: "9", y2: "21" }]
];

// node_modules/lucide/dist/esm/icons/github.js
var Github = [
  [
    "path",
    {
      d: "M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"
    }
  ],
  ["path", { d: "M9 18c-4.51 2-5-2-7-2" }]
];

// node_modules/lucide/dist/esm/icons/git-pull-request.js
var GitPullRequest = [
  ["circle", { cx: "18", cy: "18", r: "3" }],
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M13 6h3a2 2 0 0 1 2 2v7" }],
  ["line", { x1: "6", x2: "6", y1: "9", y2: "21" }]
];

// node_modules/lucide/dist/esm/icons/gitlab.js
var Gitlab = [
  [
    "path",
    {
      d: "m22 13.29-3.33-10a.42.42 0 0 0-.14-.18.38.38 0 0 0-.22-.11.39.39 0 0 0-.23.07.42.42 0 0 0-.14.18l-2.26 6.67H8.32L6.1 3.26a.42.42 0 0 0-.1-.18.38.38 0 0 0-.26-.08.39.39 0 0 0-.23.07.42.42 0 0 0-.14.18L2 13.29a.74.74 0 0 0 .27.83L12 21l9.69-6.88a.71.71 0 0 0 .31-.83Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/glass-water.js
var GlassWater = [
  [
    "path",
    {
      d: "M5.116 4.104A1 1 0 0 1 6.11 3h11.78a1 1 0 0 1 .994 1.105L17.19 20.21A2 2 0 0 1 15.2 22H8.8a2 2 0 0 1-2-1.79z"
    }
  ],
  ["path", { d: "M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0" }]
];

// node_modules/lucide/dist/esm/icons/glasses.js
var Glasses = [
  ["circle", { cx: "6", cy: "15", r: "4" }],
  ["circle", { cx: "18", cy: "15", r: "4" }],
  ["path", { d: "M14 15a2 2 0 0 0-2-2 2 2 0 0 0-2 2" }],
  ["path", { d: "M2.5 13 5 7c.7-1.3 1.4-2 3-2" }],
  ["path", { d: "M21.5 13 19 7c-.7-1.3-1.5-2-3-2" }]
];

// node_modules/lucide/dist/esm/icons/globe-lock.js
var GlobeLock = [
  ["path", { d: "M15.686 15A14.5 14.5 0 0 1 12 22a14.5 14.5 0 0 1 0-20 10 10 0 1 0 9.542 13" }],
  ["path", { d: "M2 12h8.5" }],
  ["path", { d: "M20 6V4a2 2 0 1 0-4 0v2" }],
  ["rect", { width: "8", height: "5", x: "14", y: "6", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/globe.js
var Globe = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" }],
  ["path", { d: "M2 12h20" }]
];

// node_modules/lucide/dist/esm/icons/goal.js
var Goal = [
  ["path", { d: "M12 13V2l8 4-8 4" }],
  ["path", { d: "M20.561 10.222a9 9 0 1 1-12.55-5.29" }],
  ["path", { d: "M8.002 9.997a5 5 0 1 0 8.9 2.02" }]
];

// node_modules/lucide/dist/esm/icons/grab.js
var Grab = [
  ["path", { d: "M18 11.5V9a2 2 0 0 0-2-2a2 2 0 0 0-2 2v1.4" }],
  ["path", { d: "M14 10V8a2 2 0 0 0-2-2a2 2 0 0 0-2 2v2" }],
  ["path", { d: "M10 9.9V9a2 2 0 0 0-2-2a2 2 0 0 0-2 2v5" }],
  ["path", { d: "M6 14a2 2 0 0 0-2-2a2 2 0 0 0-2 2" }],
  ["path", { d: "M18 11a2 2 0 1 1 4 0v3a8 8 0 0 1-8 8h-4a8 8 0 0 1-8-8 2 2 0 1 1 4 0" }]
];

// node_modules/lucide/dist/esm/icons/graduation-cap.js
var GraduationCap = [
  [
    "path",
    {
      d: "M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"
    }
  ],
  ["path", { d: "M22 10v6" }],
  ["path", { d: "M6 12.5V16a6 3 0 0 0 12 0v-3.5" }]
];

// node_modules/lucide/dist/esm/icons/grape.js
var Grape = [
  ["path", { d: "M22 5V2l-5.89 5.89" }],
  ["circle", { cx: "16.6", cy: "15.89", r: "3" }],
  ["circle", { cx: "8.11", cy: "7.4", r: "3" }],
  ["circle", { cx: "12.35", cy: "11.65", r: "3" }],
  ["circle", { cx: "13.91", cy: "5.85", r: "3" }],
  ["circle", { cx: "18.15", cy: "10.09", r: "3" }],
  ["circle", { cx: "6.56", cy: "13.2", r: "3" }],
  ["circle", { cx: "10.8", cy: "17.44", r: "3" }],
  ["circle", { cx: "5", cy: "19", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/grid-2x2-check.js
var Grid2x2Check = [
  [
    "path",
    {
      d: "M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3"
    }
  ],
  ["path", { d: "m16 19 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/grid-2x2-plus.js
var Grid2x2Plus = [
  [
    "path",
    {
      d: "M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3"
    }
  ],
  ["path", { d: "M16 19h6" }],
  ["path", { d: "M19 22v-6" }]
];

// node_modules/lucide/dist/esm/icons/grid-2x2-x.js
var Grid2x2X = [
  [
    "path",
    {
      d: "M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3"
    }
  ],
  ["path", { d: "m16 16 5 5" }],
  ["path", { d: "m16 21 5-5" }]
];

// node_modules/lucide/dist/esm/icons/grid-2x2.js
var Grid2x2 = [
  ["path", { d: "M12 3v18" }],
  ["path", { d: "M3 12h18" }],
  ["rect", { x: "3", y: "3", width: "18", height: "18", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/grid-3x3.js
var Grid3x3 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9h18" }],
  ["path", { d: "M3 15h18" }],
  ["path", { d: "M9 3v18" }],
  ["path", { d: "M15 3v18" }]
];

// node_modules/lucide/dist/esm/icons/grip-horizontal.js
var GripHorizontal = [
  ["circle", { cx: "12", cy: "9", r: "1" }],
  ["circle", { cx: "19", cy: "9", r: "1" }],
  ["circle", { cx: "5", cy: "9", r: "1" }],
  ["circle", { cx: "12", cy: "15", r: "1" }],
  ["circle", { cx: "19", cy: "15", r: "1" }],
  ["circle", { cx: "5", cy: "15", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/grip-vertical.js
var GripVertical = [
  ["circle", { cx: "9", cy: "12", r: "1" }],
  ["circle", { cx: "9", cy: "5", r: "1" }],
  ["circle", { cx: "9", cy: "19", r: "1" }],
  ["circle", { cx: "15", cy: "12", r: "1" }],
  ["circle", { cx: "15", cy: "5", r: "1" }],
  ["circle", { cx: "15", cy: "19", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/grip.js
var Grip = [
  ["circle", { cx: "12", cy: "5", r: "1" }],
  ["circle", { cx: "19", cy: "5", r: "1" }],
  ["circle", { cx: "5", cy: "5", r: "1" }],
  ["circle", { cx: "12", cy: "12", r: "1" }],
  ["circle", { cx: "19", cy: "12", r: "1" }],
  ["circle", { cx: "5", cy: "12", r: "1" }],
  ["circle", { cx: "12", cy: "19", r: "1" }],
  ["circle", { cx: "19", cy: "19", r: "1" }],
  ["circle", { cx: "5", cy: "19", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/group.js
var Group = [
  ["path", { d: "M3 7V5c0-1.1.9-2 2-2h2" }],
  ["path", { d: "M17 3h2c1.1 0 2 .9 2 2v2" }],
  ["path", { d: "M21 17v2c0 1.1-.9 2-2 2h-2" }],
  ["path", { d: "M7 21H5c-1.1 0-2-.9-2-2v-2" }],
  ["rect", { width: "7", height: "5", x: "7", y: "7", rx: "1" }],
  ["rect", { width: "7", height: "5", x: "10", y: "12", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/guitar.js
var Guitar = [
  ["path", { d: "m11.9 12.1 4.514-4.514" }],
  [
    "path",
    {
      d: "M20.1 2.3a1 1 0 0 0-1.4 0l-1.114 1.114A2 2 0 0 0 17 4.828v1.344a2 2 0 0 1-.586 1.414A2 2 0 0 1 17.828 7h1.344a2 2 0 0 0 1.414-.586L21.7 5.3a1 1 0 0 0 0-1.4z"
    }
  ],
  ["path", { d: "m6 16 2 2" }],
  [
    "path",
    {
      d: "M8.23 9.85A3 3 0 0 1 11 8a5 5 0 0 1 5 5 3 3 0 0 1-1.85 2.77l-.92.38A2 2 0 0 0 12 18a4 4 0 0 1-4 4 6 6 0 0 1-6-6 4 4 0 0 1 4-4 2 2 0 0 0 1.85-1.23z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/ham.js
var Ham = [
  ["path", { d: "M13.144 21.144A7.274 10.445 45 1 0 2.856 10.856" }],
  [
    "path",
    { d: "M13.144 21.144A7.274 4.365 45 0 0 2.856 10.856a7.274 4.365 45 0 0 10.288 10.288" }
  ],
  [
    "path",
    {
      d: "M16.565 10.435 18.6 8.4a2.501 2.501 0 1 0 1.65-4.65 2.5 2.5 0 1 0-4.66 1.66l-2.024 2.025"
    }
  ],
  ["path", { d: "m8.5 16.5-1-1" }]
];

// node_modules/lucide/dist/esm/icons/hamburger.js
var Hamburger = [
  ["path", { d: "M12 16H4a2 2 0 1 1 0-4h16a2 2 0 1 1 0 4h-4.25" }],
  ["path", { d: "M5 12a2 2 0 0 1-2-2 9 7 0 0 1 18 0 2 2 0 0 1-2 2" }],
  ["path", { d: "M5 16a2 2 0 0 0-2 2 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 2 2 0 0 0-2-2q0 0 0 0" }],
  ["path", { d: "m6.67 12 6.13 4.6a2 2 0 0 0 2.8-.4l3.15-4.2" }]
];

// node_modules/lucide/dist/esm/icons/hammer.js
var Hammer = [
  ["path", { d: "m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9" }],
  ["path", { d: "m18 15 4-4" }],
  [
    "path",
    {
      d: "m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/hand-coins.js
var HandCoins = [
  ["path", { d: "M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17" }],
  [
    "path",
    {
      d: "m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"
    }
  ],
  ["path", { d: "m2 16 6 6" }],
  ["circle", { cx: "16", cy: "9", r: "2.9" }],
  ["circle", { cx: "6", cy: "5", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/hand-heart.js
var HandHeart = [
  ["path", { d: "M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16" }],
  [
    "path",
    {
      d: "m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"
    }
  ],
  ["path", { d: "m2 15 6 6" }],
  [
    "path",
    {
      d: "M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/hand-helping.js
var HandHelping = [
  ["path", { d: "M11 12h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 14" }],
  [
    "path",
    {
      d: "m7 18 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"
    }
  ],
  ["path", { d: "m2 13 6 6" }]
];

// node_modules/lucide/dist/esm/icons/hand-metal.js
var HandMetal = [
  ["path", { d: "M18 12.5V10a2 2 0 0 0-2-2a2 2 0 0 0-2 2v1.4" }],
  ["path", { d: "M14 11V9a2 2 0 1 0-4 0v2" }],
  ["path", { d: "M10 10.5V5a2 2 0 1 0-4 0v9" }],
  [
    "path",
    {
      d: "m7 15-1.76-1.76a2 2 0 0 0-2.83 2.82l3.6 3.6C7.5 21.14 9.2 22 12 22h2a8 8 0 0 0 8-8V7a2 2 0 1 0-4 0v5"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/hand-platter.js
var HandPlatter = [
  ["path", { d: "M12 3V2" }],
  [
    "path",
    {
      d: "m15.4 17.4 3.2-2.8a2 2 0 1 1 2.8 2.9l-3.6 3.3c-.7.8-1.7 1.2-2.8 1.2h-4c-1.1 0-2.1-.4-2.8-1.2l-1.302-1.464A1 1 0 0 0 6.151 19H5"
    }
  ],
  ["path", { d: "M2 14h12a2 2 0 0 1 0 4h-2" }],
  ["path", { d: "M4 10h16" }],
  ["path", { d: "M5 10a7 7 0 0 1 14 0" }],
  ["path", { d: "M5 14v6a1 1 0 0 1-1 1H2" }]
];

// node_modules/lucide/dist/esm/icons/hand.js
var Hand = [
  ["path", { d: "M18 11V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2" }],
  ["path", { d: "M14 10V4a2 2 0 0 0-2-2a2 2 0 0 0-2 2v2" }],
  ["path", { d: "M10 10.5V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2v8" }],
  [
    "path",
    {
      d: "M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/handshake.js
var Handshake = [
  ["path", { d: "m11 17 2 2a1 1 0 1 0 3-3" }],
  [
    "path",
    {
      d: "m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"
    }
  ],
  ["path", { d: "m21 3 1 11h-2" }],
  ["path", { d: "M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" }],
  ["path", { d: "M3 4h8" }]
];

// node_modules/lucide/dist/esm/icons/hard-drive-download.js
var HardDriveDownload = [
  ["path", { d: "M12 2v8" }],
  ["path", { d: "m16 6-4 4-4-4" }],
  ["rect", { width: "20", height: "8", x: "2", y: "14", rx: "2" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "M10 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/hard-drive-upload.js
var HardDriveUpload = [
  ["path", { d: "m16 6-4-4-4 4" }],
  ["path", { d: "M12 2v8" }],
  ["rect", { width: "20", height: "8", x: "2", y: "14", rx: "2" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "M10 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/hard-drive.js
var HardDrive = [
  ["line", { x1: "22", x2: "2", y1: "12", y2: "12" }],
  [
    "path",
    {
      d: "M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"
    }
  ],
  ["line", { x1: "6", x2: "6.01", y1: "16", y2: "16" }],
  ["line", { x1: "10", x2: "10.01", y1: "16", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/hard-hat.js
var HardHat = [
  ["path", { d: "M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5" }],
  ["path", { d: "M14 6a6 6 0 0 1 6 6v3" }],
  ["path", { d: "M4 15v-3a6 6 0 0 1 6-6" }],
  ["rect", { x: "2", y: "15", width: "20", height: "4", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/hash.js
var Hash = [
  ["line", { x1: "4", x2: "20", y1: "9", y2: "9" }],
  ["line", { x1: "4", x2: "20", y1: "15", y2: "15" }],
  ["line", { x1: "10", x2: "8", y1: "3", y2: "21" }],
  ["line", { x1: "16", x2: "14", y1: "3", y2: "21" }]
];

// node_modules/lucide/dist/esm/icons/haze.js
var Haze = [
  ["path", { d: "m5.2 6.2 1.4 1.4" }],
  ["path", { d: "M2 13h2" }],
  ["path", { d: "M20 13h2" }],
  ["path", { d: "m17.4 7.6 1.4-1.4" }],
  ["path", { d: "M22 17H2" }],
  ["path", { d: "M22 21H2" }],
  ["path", { d: "M16 13a4 4 0 0 0-8 0" }],
  ["path", { d: "M12 5V2.5" }]
];

// node_modules/lucide/dist/esm/icons/hdmi-port.js
var HdmiPort = [
  [
    "path",
    { d: "M22 9a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h1l2 2h12l2-2h1a1 1 0 0 0 1-1Z" }
  ],
  ["path", { d: "M7.5 12h9" }]
];

// node_modules/lucide/dist/esm/icons/heading-1.js
var Heading1 = [
  ["path", { d: "M4 12h8" }],
  ["path", { d: "M4 18V6" }],
  ["path", { d: "M12 18V6" }],
  ["path", { d: "m17 12 3-2v8" }]
];

// node_modules/lucide/dist/esm/icons/heading-2.js
var Heading2 = [
  ["path", { d: "M4 12h8" }],
  ["path", { d: "M4 18V6" }],
  ["path", { d: "M12 18V6" }],
  ["path", { d: "M21 18h-4c0-4 4-3 4-6 0-1.5-2-2.5-4-1" }]
];

// node_modules/lucide/dist/esm/icons/heading-3.js
var Heading3 = [
  ["path", { d: "M4 12h8" }],
  ["path", { d: "M4 18V6" }],
  ["path", { d: "M12 18V6" }],
  ["path", { d: "M17.5 10.5c1.7-1 3.5 0 3.5 1.5a2 2 0 0 1-2 2" }],
  ["path", { d: "M17 17.5c2 1.5 4 .3 4-1.5a2 2 0 0 0-2-2" }]
];

// node_modules/lucide/dist/esm/icons/heading-5.js
var Heading5 = [
  ["path", { d: "M4 12h8" }],
  ["path", { d: "M4 18V6" }],
  ["path", { d: "M12 18V6" }],
  ["path", { d: "M17 13v-3h4" }],
  ["path", { d: "M17 17.7c.4.2.8.3 1.3.3 1.5 0 2.7-1.1 2.7-2.5S19.8 13 18.3 13H17" }]
];

// node_modules/lucide/dist/esm/icons/heading-6.js
var Heading6 = [
  ["path", { d: "M4 12h8" }],
  ["path", { d: "M4 18V6" }],
  ["path", { d: "M12 18V6" }],
  ["circle", { cx: "19", cy: "16", r: "2" }],
  ["path", { d: "M20 10c-2 2-3 3.5-3 6" }]
];

// node_modules/lucide/dist/esm/icons/heading-4.js
var Heading4 = [
  ["path", { d: "M12 18V6" }],
  ["path", { d: "M17 10v3a1 1 0 0 0 1 1h3" }],
  ["path", { d: "M21 10v8" }],
  ["path", { d: "M4 12h8" }],
  ["path", { d: "M4 18V6" }]
];

// node_modules/lucide/dist/esm/icons/heading.js
var Heading = [
  ["path", { d: "M6 12h12" }],
  ["path", { d: "M6 20V4" }],
  ["path", { d: "M18 20V4" }]
];

// node_modules/lucide/dist/esm/icons/headphone-off.js
var HeadphoneOff = [
  ["path", { d: "M21 14h-1.343" }],
  ["path", { d: "M9.128 3.47A9 9 0 0 1 21 12v3.343" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M20.414 20.414A2 2 0 0 1 19 21h-1a2 2 0 0 1-2-2v-3" }],
  ["path", { d: "M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 2.636-6.364" }]
];

// node_modules/lucide/dist/esm/icons/headphones.js
var Headphones = [
  [
    "path",
    {
      d: "M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/headset.js
var Headset = [
  [
    "path",
    {
      d: "M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"
    }
  ],
  ["path", { d: "M21 16v2a4 4 0 0 1-4 4h-5" }]
];

// node_modules/lucide/dist/esm/icons/heart-crack.js
var HeartCrack = [
  [
    "path",
    {
      d: "M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
    }
  ],
  ["path", { d: "m12 13-1-1 2-2-3-3 2-2" }]
];

// node_modules/lucide/dist/esm/icons/heart-handshake.js
var HeartHandshake = [
  [
    "path",
    {
      d: "M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
    }
  ],
  [
    "path",
    {
      d: "M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08c.82.82 2.13.85 3 .07l2.07-1.9a2.82 2.82 0 0 1 3.79 0l2.96 2.66"
    }
  ],
  ["path", { d: "m18 15-2-2" }],
  ["path", { d: "m15 18-2-2" }]
];

// node_modules/lucide/dist/esm/icons/heart-off.js
var HeartOff = [
  ["line", { x1: "2", y1: "2", x2: "22", y2: "22" }],
  ["path", { d: "M16.5 16.5 12 21l-7-7c-1.5-1.45-3-3.2-3-5.5a5.5 5.5 0 0 1 2.14-4.35" }],
  [
    "path",
    {
      d: "M8.76 3.1c1.15.22 2.13.78 3.24 1.9 1.5-1.5 2.74-2 4.5-2A5.5 5.5 0 0 1 22 8.5c0 2.12-1.3 3.78-2.67 5.17"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/heart-minus.js
var HeartMinus = [
  [
    "path",
    {
      d: "M13.5 19.5 12 21l-7-7c-1.5-1.45-3-3.2-3-5.5A5.5 5.5 0 0 1 7.5 3c1.76 0 3 .5 4.5 2 1.5-1.5 2.74-2 4.5-2a5.5 5.5 0 0 1 5.402 6.5"
    }
  ],
  ["path", { d: "M15 15h6" }]
];

// node_modules/lucide/dist/esm/icons/heart-plus.js
var HeartPlus = [
  [
    "path",
    {
      d: "M13.5 19.5 12 21l-7-7c-1.5-1.45-3-3.2-3-5.5A5.5 5.5 0 0 1 7.5 3c1.76 0 3 .5 4.5 2 1.5-1.5 2.74-2 4.5-2a5.5 5.5 0 0 1 5.402 6.5"
    }
  ],
  ["path", { d: "M15 15h6" }],
  ["path", { d: "M18 12v6" }]
];

// node_modules/lucide/dist/esm/icons/heart-pulse.js
var HeartPulse = [
  [
    "path",
    {
      d: "M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
    }
  ],
  ["path", { d: "M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27" }]
];

// node_modules/lucide/dist/esm/icons/heart.js
var Heart = [
  [
    "path",
    {
      d: "M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/heater.js
var Heater = [
  ["path", { d: "M11 8c2-3-2-3 0-6" }],
  ["path", { d: "M15.5 8c2-3-2-3 0-6" }],
  ["path", { d: "M6 10h.01" }],
  ["path", { d: "M6 14h.01" }],
  ["path", { d: "M10 16v-4" }],
  ["path", { d: "M14 16v-4" }],
  ["path", { d: "M18 16v-4" }],
  ["path", { d: "M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3" }],
  ["path", { d: "M5 20v2" }],
  ["path", { d: "M19 20v2" }]
];

// node_modules/lucide/dist/esm/icons/hexagon.js
var Hexagon = [
  [
    "path",
    {
      d: "M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/highlighter.js
var Highlighter = [
  ["path", { d: "m9 11-6 6v3h9l3-3" }],
  ["path", { d: "m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4" }]
];

// node_modules/lucide/dist/esm/icons/hop-off.js
var HopOff = [
  ["path", { d: "M10.82 16.12c1.69.6 3.91.79 5.18.85.28.01.53-.09.7-.27" }],
  [
    "path",
    { d: "M11.14 20.57c.52.24 2.44 1.12 4.08 1.37.46.06.86-.25.9-.71.12-1.52-.3-3.43-.5-4.28" }
  ],
  ["path", { d: "M16.13 21.05c1.65.63 3.68.84 4.87.91a.9.9 0 0 0 .7-.26" }],
  [
    "path",
    { d: "M17.99 5.52a20.83 20.83 0 0 1 3.15 4.5.8.8 0 0 1-.68 1.13c-1.17.1-2.5.02-3.9-.25" }
  ],
  ["path", { d: "M20.57 11.14c.24.52 1.12 2.44 1.37 4.08.04.3-.08.59-.31.75" }],
  [
    "path",
    {
      d: "M4.93 4.93a10 10 0 0 0-.67 13.4c.35.43.96.4 1.17-.12.69-1.71 1.07-5.07 1.07-6.71 1.34.45 3.1.9 4.88.62a.85.85 0 0 0 .48-.24"
    }
  ],
  [
    "path",
    { d: "M5.52 17.99c1.05.95 2.91 2.42 4.5 3.15a.8.8 0 0 0 1.13-.68c.2-2.34-.33-5.3-1.57-8.28" }
  ],
  ["path", { d: "M8.35 2.68a10 10 0 0 1 9.98 1.58c.43.35.4.96-.12 1.17-1.5.6-4.3.98-6.07 1.05" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/history.js
var History = [
  ["path", { d: "M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" }],
  ["path", { d: "M3 3v5h5" }],
  ["path", { d: "M12 7v5l4 2" }]
];

// node_modules/lucide/dist/esm/icons/hop.js
var Hop = [
  [
    "path",
    { d: "M10.82 16.12c1.69.6 3.91.79 5.18.85.55.03 1-.42.97-.97-.06-1.27-.26-3.5-.85-5.18" }
  ],
  [
    "path",
    {
      d: "M11.5 6.5c1.64 0 5-.38 6.71-1.07.52-.2.55-.82.12-1.17A10 10 0 0 0 4.26 18.33c.35.43.96.4 1.17-.12.69-1.71 1.07-5.07 1.07-6.71 1.34.45 3.1.9 4.88.62a.88.88 0 0 0 .73-.74c.3-2.14-.15-3.5-.61-4.88"
    }
  ],
  [
    "path",
    { d: "M15.62 16.95c.2.85.62 2.76.5 4.28a.77.77 0 0 1-.9.7 16.64 16.64 0 0 1-4.08-1.36" }
  ],
  [
    "path",
    { d: "M16.13 21.05c1.65.63 3.68.84 4.87.91a.9.9 0 0 0 .96-.96 17.68 17.68 0 0 0-.9-4.87" }
  ],
  [
    "path",
    { d: "M16.94 15.62c.86.2 2.77.62 4.29.5a.77.77 0 0 0 .7-.9 16.64 16.64 0 0 0-1.36-4.08" }
  ],
  [
    "path",
    { d: "M17.99 5.52a20.82 20.82 0 0 1 3.15 4.5.8.8 0 0 1-.68 1.13c-2.33.2-5.3-.32-8.27-1.57" }
  ],
  ["path", { d: "M4.93 4.93 3 3a.7.7 0 0 1 0-1" }],
  [
    "path",
    {
      d: "M9.58 12.18c1.24 2.98 1.77 5.95 1.57 8.28a.8.8 0 0 1-1.13.68 20.82 20.82 0 0 1-4.5-3.15"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/hospital.js
var Hospital = [
  ["path", { d: "M12 6v4" }],
  ["path", { d: "M14 14h-4" }],
  ["path", { d: "M14 18h-4" }],
  ["path", { d: "M14 8h-4" }],
  ["path", { d: "M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18" }]
];

// node_modules/lucide/dist/esm/icons/hotel.js
var Hotel = [
  ["path", { d: "M10 22v-6.57" }],
  ["path", { d: "M12 11h.01" }],
  ["path", { d: "M12 7h.01" }],
  ["path", { d: "M14 15.43V22" }],
  ["path", { d: "M15 16a5 5 0 0 0-6 0" }],
  ["path", { d: "M16 11h.01" }],
  ["path", { d: "M16 7h.01" }],
  ["path", { d: "M8 11h.01" }],
  ["path", { d: "M8 7h.01" }],
  ["rect", { x: "4", y: "2", width: "16", height: "20", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/hourglass.js
var Hourglass = [
  ["path", { d: "M5 22h14" }],
  ["path", { d: "M5 2h14" }],
  ["path", { d: "M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" }],
  ["path", { d: "M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2" }]
];

// node_modules/lucide/dist/esm/icons/house-plug.js
var HousePlug = [
  ["path", { d: "M10 12V8.964" }],
  ["path", { d: "M14 12V8.964" }],
  ["path", { d: "M15 12a1 1 0 0 1 1 1v2a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2a1 1 0 0 1 1-1z" }],
  [
    "path",
    {
      d: "M8.5 21H5a2 2 0 0 1-2-2v-9a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2v-2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/house-wifi.js
var HouseWifi = [
  ["path", { d: "M9.5 13.866a4 4 0 0 1 5 .01" }],
  ["path", { d: "M12 17h.01" }],
  [
    "path",
    {
      d: "M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
    }
  ],
  ["path", { d: "M7 10.754a8 8 0 0 1 10 0" }]
];

// node_modules/lucide/dist/esm/icons/house-plus.js
var HousePlus = [
  [
    "path",
    {
      d: "M13.22 2.416a2 2 0 0 0-2.511.057l-7 5.999A2 2 0 0 0 3 10v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7.354"
    }
  ],
  ["path", { d: "M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" }],
  ["path", { d: "M15 6h6" }],
  ["path", { d: "M18 3v6" }]
];

// node_modules/lucide/dist/esm/icons/house.js
var House = [
  ["path", { d: "M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" }],
  [
    "path",
    {
      d: "M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/ice-cream-bowl.js
var IceCreamBowl = [
  [
    "path",
    { d: "M12 17c5 0 8-2.69 8-6H4c0 3.31 3 6 8 6m-4 4h8m-4-3v3M5.14 11a3.5 3.5 0 1 1 6.71 0" }
  ],
  ["path", { d: "M12.14 11a3.5 3.5 0 1 1 6.71 0" }],
  ["path", { d: "M15.5 6.5a3.5 3.5 0 1 0-7 0" }]
];

// node_modules/lucide/dist/esm/icons/ice-cream-cone.js
var IceCreamCone = [
  ["path", { d: "m7 11 4.08 10.35a1 1 0 0 0 1.84 0L17 11" }],
  ["path", { d: "M17 7A5 5 0 0 0 7 7" }],
  ["path", { d: "M17 7a2 2 0 0 1 0 4H7a2 2 0 0 1 0-4" }]
];

// node_modules/lucide/dist/esm/icons/id-card.js
var IdCard = [
  ["path", { d: "M16 10h2" }],
  ["path", { d: "M16 14h2" }],
  ["path", { d: "M6.17 15a3 3 0 0 1 5.66 0" }],
  ["circle", { cx: "9", cy: "11", r: "2" }],
  ["rect", { x: "2", y: "5", width: "20", height: "14", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/image-down.js
var ImageDown = [
  [
    "path",
    {
      d: "M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"
    }
  ],
  ["path", { d: "m14 19 3 3v-5.5" }],
  ["path", { d: "m17 22 3-3" }],
  ["circle", { cx: "9", cy: "9", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/image-minus.js
var ImageMinus = [
  ["path", { d: "M21 9v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7" }],
  ["line", { x1: "16", x2: "22", y1: "5", y2: "5" }],
  ["circle", { cx: "9", cy: "9", r: "2" }],
  ["path", { d: "m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" }]
];

// node_modules/lucide/dist/esm/icons/image-off.js
var ImageOff = [
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }],
  ["path", { d: "M10.41 10.41a2 2 0 1 1-2.83-2.83" }],
  ["line", { x1: "13.5", x2: "6", y1: "13.5", y2: "21" }],
  ["line", { x1: "18", x2: "21", y1: "12", y2: "15" }],
  ["path", { d: "M3.59 3.59A1.99 1.99 0 0 0 3 5v14a2 2 0 0 0 2 2h14c.55 0 1.052-.22 1.41-.59" }],
  ["path", { d: "M21 15V5a2 2 0 0 0-2-2H9" }]
];

// node_modules/lucide/dist/esm/icons/image-play.js
var ImagePlay = [
  ["path", { d: "m11 16-5 5" }],
  ["path", { d: "M11 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6.5" }],
  [
    "path",
    {
      d: "M15.765 22a.5.5 0 0 1-.765-.424V13.38a.5.5 0 0 1 .765-.424l5.878 3.674a1 1 0 0 1 0 1.696z"
    }
  ],
  ["circle", { cx: "9", cy: "9", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/image-plus.js
var ImagePlus = [
  ["path", { d: "M16 5h6" }],
  ["path", { d: "M19 2v6" }],
  ["path", { d: "M21 11.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7.5" }],
  ["path", { d: "m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" }],
  ["circle", { cx: "9", cy: "9", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/image-up.js
var ImageUp = [
  [
    "path",
    {
      d: "M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"
    }
  ],
  ["path", { d: "m14 19.5 3-3 3 3" }],
  ["path", { d: "M17 22v-5.5" }],
  ["circle", { cx: "9", cy: "9", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/image-upscale.js
var ImageUpscale = [
  ["path", { d: "M16 3h5v5" }],
  ["path", { d: "M17 21h2a2 2 0 0 0 2-2" }],
  ["path", { d: "M21 12v3" }],
  ["path", { d: "m21 3-5 5" }],
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2" }],
  ["path", { d: "m5 21 4.144-4.144a1.21 1.21 0 0 1 1.712 0L13 19" }],
  ["path", { d: "M9 3h3" }],
  ["rect", { x: "3", y: "11", width: "10", height: "10", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/image.js
var Image = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["circle", { cx: "9", cy: "9", r: "2" }],
  ["path", { d: "m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" }]
];

// node_modules/lucide/dist/esm/icons/images.js
var Images = [
  ["path", { d: "M18 22H4a2 2 0 0 1-2-2V6" }],
  ["path", { d: "m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" }],
  ["circle", { cx: "12", cy: "8", r: "2" }],
  ["rect", { width: "16", height: "16", x: "6", y: "2", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/import.js
var Import = [
  ["path", { d: "M12 3v12" }],
  ["path", { d: "m8 11 4 4 4-4" }],
  ["path", { d: "M8 5H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-4" }]
];

// node_modules/lucide/dist/esm/icons/inbox.js
var Inbox = [
  ["polyline", { points: "22 12 16 12 14 15 10 15 8 12 2 12" }],
  [
    "path",
    {
      d: "M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/indent-decrease.js
var IndentDecrease = [
  ["path", { d: "M21 12H11" }],
  ["path", { d: "M21 18H11" }],
  ["path", { d: "M21 6H11" }],
  ["path", { d: "m7 8-4 4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/indent-increase.js
var IndentIncrease = [
  ["path", { d: "M21 12H11" }],
  ["path", { d: "M21 18H11" }],
  ["path", { d: "M21 6H11" }],
  ["path", { d: "m3 8 4 4-4 4" }]
];

// node_modules/lucide/dist/esm/icons/indian-rupee.js
var IndianRupee = [
  ["path", { d: "M6 3h12" }],
  ["path", { d: "M6 8h12" }],
  ["path", { d: "m6 13 8.5 8" }],
  ["path", { d: "M6 13h3" }],
  ["path", { d: "M9 13c6.667 0 6.667-10 0-10" }]
];

// node_modules/lucide/dist/esm/icons/infinity.js
var Infinity = [
  ["path", { d: "M6 16c5 0 7-8 12-8a4 4 0 0 1 0 8c-5 0-7-8-12-8a4 4 0 1 0 0 8" }]
];

// node_modules/lucide/dist/esm/icons/info.js
var Info = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M12 16v-4" }],
  ["path", { d: "M12 8h.01" }]
];

// node_modules/lucide/dist/esm/icons/inspection-panel.js
var InspectionPanel = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 7h.01" }],
  ["path", { d: "M17 7h.01" }],
  ["path", { d: "M7 17h.01" }],
  ["path", { d: "M17 17h.01" }]
];

// node_modules/lucide/dist/esm/icons/instagram.js
var Instagram = [
  ["rect", { width: "20", height: "20", x: "2", y: "2", rx: "5", ry: "5" }],
  ["path", { d: "M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" }],
  ["line", { x1: "17.5", x2: "17.51", y1: "6.5", y2: "6.5" }]
];

// node_modules/lucide/dist/esm/icons/italic.js
var Italic = [
  ["line", { x1: "19", x2: "10", y1: "4", y2: "4" }],
  ["line", { x1: "14", x2: "5", y1: "20", y2: "20" }],
  ["line", { x1: "15", x2: "9", y1: "4", y2: "20" }]
];

// node_modules/lucide/dist/esm/icons/iteration-ccw.js
var IterationCcw = [
  ["path", { d: "M20 10c0-4.4-3.6-8-8-8s-8 3.6-8 8 3.6 8 8 8h8" }],
  ["polyline", { points: "16 14 20 18 16 22" }]
];

// node_modules/lucide/dist/esm/icons/iteration-cw.js
var IterationCw = [
  ["path", { d: "M4 10c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8H4" }],
  ["polyline", { points: "8 22 4 18 8 14" }]
];

// node_modules/lucide/dist/esm/icons/japanese-yen.js
var JapaneseYen = [
  ["path", { d: "M12 9.5V21m0-11.5L6 3m6 6.5L18 3" }],
  ["path", { d: "M6 15h12" }],
  ["path", { d: "M6 11h12" }]
];

// node_modules/lucide/dist/esm/icons/joystick.js
var Joystick = [
  ["path", { d: "M21 17a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2Z" }],
  ["path", { d: "M6 15v-2" }],
  ["path", { d: "M12 15V9" }],
  ["circle", { cx: "12", cy: "6", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/kanban.js
var Kanban = [
  ["path", { d: "M6 5v11" }],
  ["path", { d: "M12 5v6" }],
  ["path", { d: "M18 5v14" }]
];

// node_modules/lucide/dist/esm/icons/key-round.js
var KeyRound = [
  [
    "path",
    {
      d: "M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"
    }
  ],
  ["circle", { cx: "16.5", cy: "7.5", r: ".5", fill: "currentColor" }]
];

// node_modules/lucide/dist/esm/icons/key-square.js
var KeySquare = [
  [
    "path",
    {
      d: "M12.4 2.7a2.5 2.5 0 0 1 3.4 0l5.5 5.5a2.5 2.5 0 0 1 0 3.4l-3.7 3.7a2.5 2.5 0 0 1-3.4 0L8.7 9.8a2.5 2.5 0 0 1 0-3.4z"
    }
  ],
  ["path", { d: "m14 7 3 3" }],
  [
    "path",
    {
      d: "m9.4 10.6-6.814 6.814A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/key.js
var Key = [
  ["path", { d: "m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4" }],
  ["path", { d: "m21 2-9.6 9.6" }],
  ["circle", { cx: "7.5", cy: "15.5", r: "5.5" }]
];

// node_modules/lucide/dist/esm/icons/keyboard-music.js
var KeyboardMusic = [
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["path", { d: "M6 8h4" }],
  ["path", { d: "M14 8h.01" }],
  ["path", { d: "M18 8h.01" }],
  ["path", { d: "M2 12h20" }],
  ["path", { d: "M6 12v4" }],
  ["path", { d: "M10 12v4" }],
  ["path", { d: "M14 12v4" }],
  ["path", { d: "M18 12v4" }]
];

// node_modules/lucide/dist/esm/icons/keyboard.js
var Keyboard = [
  ["path", { d: "M10 8h.01" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M14 8h.01" }],
  ["path", { d: "M16 12h.01" }],
  ["path", { d: "M18 8h.01" }],
  ["path", { d: "M6 8h.01" }],
  ["path", { d: "M7 16h10" }],
  ["path", { d: "M8 12h.01" }],
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/keyboard-off.js
var KeyboardOff = [
  ["path", { d: "M 20 4 A2 2 0 0 1 22 6" }],
  ["path", { d: "M 22 6 L 22 16.41" }],
  ["path", { d: "M 7 16 L 16 16" }],
  ["path", { d: "M 9.69 4 L 20 4" }],
  ["path", { d: "M14 8h.01" }],
  ["path", { d: "M18 8h.01" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M20 20H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2" }],
  ["path", { d: "M6 8h.01" }],
  ["path", { d: "M8 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/lamp-ceiling.js
var LampCeiling = [
  ["path", { d: "M12 2v5" }],
  ["path", { d: "M14.829 15.998a3 3 0 1 1-5.658 0" }],
  [
    "path",
    {
      d: "M20.92 14.606A1 1 0 0 1 20 16H4a1 1 0 0 1-.92-1.394l3-7A1 1 0 0 1 7 7h10a1 1 0 0 1 .92.606z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/lamp-floor.js
var LampFloor = [
  ["path", { d: "M12 10v12" }],
  [
    "path",
    {
      d: "M17.929 7.629A1 1 0 0 1 17 9H7a1 1 0 0 1-.928-1.371l2-5A1 1 0 0 1 9 2h6a1 1 0 0 1 .928.629z"
    }
  ],
  ["path", { d: "M9 22h6" }]
];

// node_modules/lucide/dist/esm/icons/lamp-desk.js
var LampDesk = [
  [
    "path",
    {
      d: "M10.293 2.293a1 1 0 0 1 1.414 0l2.5 2.5 5.994 1.227a1 1 0 0 1 .506 1.687l-7 7a1 1 0 0 1-1.687-.506l-1.227-5.994-2.5-2.5a1 1 0 0 1 0-1.414z"
    }
  ],
  ["path", { d: "m14.207 4.793-3.414 3.414" }],
  ["path", { d: "M3 20a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z" }],
  ["path", { d: "m9.086 6.5-4.793 4.793a1 1 0 0 0-.18 1.17L7 18" }]
];

// node_modules/lucide/dist/esm/icons/lamp-wall-down.js
var LampWallDown = [
  [
    "path",
    {
      d: "M19.929 18.629A1 1 0 0 1 19 20H9a1 1 0 0 1-.928-1.371l2-5A1 1 0 0 1 11 13h6a1 1 0 0 1 .928.629z"
    }
  ],
  ["path", { d: "M6 3a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z" }],
  ["path", { d: "M8 6h4a2 2 0 0 1 2 2v5" }]
];

// node_modules/lucide/dist/esm/icons/lamp-wall-up.js
var LampWallUp = [
  [
    "path",
    {
      d: "M19.929 9.629A1 1 0 0 1 19 11H9a1 1 0 0 1-.928-1.371l2-5A1 1 0 0 1 11 4h6a1 1 0 0 1 .928.629z"
    }
  ],
  ["path", { d: "M6 15a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" }],
  ["path", { d: "M8 18h4a2 2 0 0 0 2-2v-5" }]
];

// node_modules/lucide/dist/esm/icons/land-plot.js
var LandPlot = [
  ["path", { d: "m12 8 6-3-6-3v10" }],
  [
    "path",
    {
      d: "m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"
    }
  ],
  ["path", { d: "m6.49 12.85 11.02 6.3" }],
  ["path", { d: "M17.51 12.85 6.5 19.15" }]
];

// node_modules/lucide/dist/esm/icons/lamp.js
var Lamp = [
  ["path", { d: "M12 12v6" }],
  [
    "path",
    {
      d: "M4.077 10.615A1 1 0 0 0 5 12h14a1 1 0 0 0 .923-1.385l-3.077-7.384A2 2 0 0 0 15 2H9a2 2 0 0 0-1.846 1.23Z"
    }
  ],
  ["path", { d: "M8 20a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1z" }]
];

// node_modules/lucide/dist/esm/icons/landmark.js
var Landmark = [
  ["line", { x1: "3", x2: "21", y1: "22", y2: "22" }],
  ["line", { x1: "6", x2: "6", y1: "18", y2: "11" }],
  ["line", { x1: "10", x2: "10", y1: "18", y2: "11" }],
  ["line", { x1: "14", x2: "14", y1: "18", y2: "11" }],
  ["line", { x1: "18", x2: "18", y1: "18", y2: "11" }],
  ["polygon", { points: "12 2 20 7 4 7" }]
];

// node_modules/lucide/dist/esm/icons/languages.js
var Languages = [
  ["path", { d: "m5 8 6 6" }],
  ["path", { d: "m4 14 6-6 2-3" }],
  ["path", { d: "M2 5h12" }],
  ["path", { d: "M7 2h1" }],
  ["path", { d: "m22 22-5-10-5 10" }],
  ["path", { d: "M14 18h6" }]
];

// node_modules/lucide/dist/esm/icons/laptop-minimal-check.js
var LaptopMinimalCheck = [
  ["path", { d: "M2 20h20" }],
  ["path", { d: "m9 10 2 2 4-4" }],
  ["rect", { x: "3", y: "4", width: "18", height: "12", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/laptop-minimal.js
var LaptopMinimal = [
  ["rect", { width: "18", height: "12", x: "3", y: "4", rx: "2", ry: "2" }],
  ["line", { x1: "2", x2: "22", y1: "20", y2: "20" }]
];

// node_modules/lucide/dist/esm/icons/laptop.js
var Laptop = [
  [
    "path",
    {
      d: "M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/lasso-select.js
var LassoSelect = [
  ["path", { d: "M7 22a5 5 0 0 1-2-4" }],
  ["path", { d: "M7 16.93c.96.43 1.96.74 2.99.91" }],
  [
    "path",
    { d: "M3.34 14A6.8 6.8 0 0 1 2 10c0-4.42 4.48-8 10-8s10 3.58 10 8a7.19 7.19 0 0 1-.33 2" }
  ],
  ["path", { d: "M5 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" }],
  [
    "path",
    {
      d: "M14.33 22h-.09a.35.35 0 0 1-.24-.32v-10a.34.34 0 0 1 .33-.34c.08 0 .15.03.21.08l7.34 6a.33.33 0 0 1-.21.59h-4.49l-2.57 3.85a.35.35 0 0 1-.28.14z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/lasso.js
var Lasso = [
  ["path", { d: "M7 22a5 5 0 0 1-2-4" }],
  [
    "path",
    { d: "M3.3 14A6.8 6.8 0 0 1 2 10c0-4.4 4.5-8 10-8s10 3.6 10 8-4.5 8-10 8a12 12 0 0 1-5-1" }
  ],
  ["path", { d: "M5 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" }]
];

// node_modules/lucide/dist/esm/icons/laugh.js
var Laugh = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M18 13a6 6 0 0 1-6 5 6 6 0 0 1-6-5h12Z" }],
  ["line", { x1: "9", x2: "9.01", y1: "9", y2: "9" }],
  ["line", { x1: "15", x2: "15.01", y1: "9", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/layers-2.js
var Layers2 = [
  [
    "path",
    {
      d: "M13 13.74a2 2 0 0 1-2 0L2.5 8.87a1 1 0 0 1 0-1.74L11 2.26a2 2 0 0 1 2 0l8.5 4.87a1 1 0 0 1 0 1.74z"
    }
  ],
  [
    "path",
    {
      d: "m20 14.285 1.5.845a1 1 0 0 1 0 1.74L13 21.74a2 2 0 0 1-2 0l-8.5-4.87a1 1 0 0 1 0-1.74l1.5-.845"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/layers.js
var Layers = [
  [
    "path",
    {
      d: "M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"
    }
  ],
  ["path", { d: "M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12" }],
  ["path", { d: "M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17" }]
];

// node_modules/lucide/dist/esm/icons/layout-dashboard.js
var LayoutDashboard = [
  ["rect", { width: "7", height: "9", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "5", x: "14", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "9", x: "14", y: "12", rx: "1" }],
  ["rect", { width: "7", height: "5", x: "3", y: "16", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/layout-grid.js
var LayoutGrid = [
  ["rect", { width: "7", height: "7", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "14", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "14", y: "14", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "3", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/layout-list.js
var LayoutList = [
  ["rect", { width: "7", height: "7", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "3", y: "14", rx: "1" }],
  ["path", { d: "M14 4h7" }],
  ["path", { d: "M14 9h7" }],
  ["path", { d: "M14 15h7" }],
  ["path", { d: "M14 20h7" }]
];

// node_modules/lucide/dist/esm/icons/layout-panel-left.js
var LayoutPanelLeft = [
  ["rect", { width: "7", height: "18", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "14", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "14", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/layout-panel-top.js
var LayoutPanelTop = [
  ["rect", { width: "18", height: "7", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "3", y: "14", rx: "1" }],
  ["rect", { width: "7", height: "7", x: "14", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/layout-template.js
var LayoutTemplate = [
  ["rect", { width: "18", height: "7", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "9", height: "7", x: "3", y: "14", rx: "1" }],
  ["rect", { width: "5", height: "7", x: "16", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/leaf.js
var Leaf = [
  [
    "path",
    { d: "M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" }
  ],
  ["path", { d: "M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" }]
];

// node_modules/lucide/dist/esm/icons/leafy-green.js
var LeafyGreen = [
  [
    "path",
    {
      d: "M2 22c1.25-.987 2.27-1.975 3.9-2.2a5.56 5.56 0 0 1 3.8 1.5 4 4 0 0 0 6.187-2.353 3.5 3.5 0 0 0 3.69-5.116A3.5 3.5 0 0 0 20.95 8 3.5 3.5 0 1 0 16 3.05a3.5 3.5 0 0 0-5.831 1.373 3.5 3.5 0 0 0-5.116 3.69 4 4 0 0 0-2.348 6.155C3.499 15.42 4.409 16.712 4.2 18.1 3.926 19.743 3.014 20.732 2 22"
    }
  ],
  ["path", { d: "M2 22 17 7" }]
];

// node_modules/lucide/dist/esm/icons/lectern.js
var Lectern = [
  [
    "path",
    {
      d: "M16 12h3a2 2 0 0 0 1.902-1.38l1.056-3.333A1 1 0 0 0 21 6H3a1 1 0 0 0-.958 1.287l1.056 3.334A2 2 0 0 0 5 12h3"
    }
  ],
  ["path", { d: "M18 6V3a1 1 0 0 0-1-1h-3" }],
  ["rect", { width: "8", height: "12", x: "8", y: "10", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/letter-text.js
var LetterText = [
  ["path", { d: "M15 12h6" }],
  ["path", { d: "M15 6h6" }],
  ["path", { d: "m3 13 3.553-7.724a.5.5 0 0 1 .894 0L11 13" }],
  ["path", { d: "M3 18h18" }],
  ["path", { d: "M3.92 11h6.16" }]
];

// node_modules/lucide/dist/esm/icons/library-big.js
var LibraryBig = [
  ["rect", { width: "8", height: "18", x: "3", y: "3", rx: "1" }],
  ["path", { d: "M7 3v18" }],
  [
    "path",
    {
      d: "M20.4 18.9c.2.5-.1 1.1-.6 1.3l-1.9.7c-.5.2-1.1-.1-1.3-.6L11.1 5.1c-.2-.5.1-1.1.6-1.3l1.9-.7c.5-.2 1.1.1 1.3.6Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/library.js
var Library = [
  ["path", { d: "m16 6 4 14" }],
  ["path", { d: "M12 6v14" }],
  ["path", { d: "M8 8v12" }],
  ["path", { d: "M4 4v16" }]
];

// node_modules/lucide/dist/esm/icons/life-buoy.js
var LifeBuoy = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "m4.93 4.93 4.24 4.24" }],
  ["path", { d: "m14.83 9.17 4.24-4.24" }],
  ["path", { d: "m14.83 14.83 4.24 4.24" }],
  ["path", { d: "m9.17 14.83-4.24 4.24" }],
  ["circle", { cx: "12", cy: "12", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/ligature.js
var Ligature = [
  ["path", { d: "M8 20V8c0-2.2 1.8-4 4-4 1.5 0 2.8.8 3.5 2" }],
  ["path", { d: "M6 12h4" }],
  ["path", { d: "M14 12h2v8" }],
  ["path", { d: "M6 20h4" }],
  ["path", { d: "M14 20h4" }]
];

// node_modules/lucide/dist/esm/icons/lightbulb-off.js
var LightbulbOff = [
  ["path", { d: "M16.8 11.2c.8-.9 1.2-2 1.2-3.2a6 6 0 0 0-9.3-5" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M6.3 6.3a4.67 4.67 0 0 0 1.2 5.2c.7.7 1.3 1.5 1.5 2.5" }],
  ["path", { d: "M9 18h6" }],
  ["path", { d: "M10 22h4" }]
];

// node_modules/lucide/dist/esm/icons/lightbulb.js
var Lightbulb = [
  [
    "path",
    {
      d: "M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"
    }
  ],
  ["path", { d: "M9 18h6" }],
  ["path", { d: "M10 22h4" }]
];

// node_modules/lucide/dist/esm/icons/link-2-off.js
var Link2Off = [
  ["path", { d: "M9 17H7A5 5 0 0 1 7 7" }],
  ["path", { d: "M15 7h2a5 5 0 0 1 4 8" }],
  ["line", { x1: "8", x2: "12", y1: "12", y2: "12" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/link-2.js
var Link2 = [
  ["path", { d: "M9 17H7A5 5 0 0 1 7 7h2" }],
  ["path", { d: "M15 7h2a5 5 0 1 1 0 10h-2" }],
  ["line", { x1: "8", x2: "16", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/link.js
var Link = [
  ["path", { d: "M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" }],
  ["path", { d: "M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" }]
];

// node_modules/lucide/dist/esm/icons/linkedin.js
var Linkedin = [
  ["path", { d: "M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" }],
  ["rect", { width: "4", height: "12", x: "2", y: "9" }],
  ["circle", { cx: "4", cy: "4", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/list-check.js
var ListCheck = [
  ["path", { d: "M11 18H3" }],
  ["path", { d: "m15 18 2 2 4-4" }],
  ["path", { d: "M16 12H3" }],
  ["path", { d: "M16 6H3" }]
];

// node_modules/lucide/dist/esm/icons/list-checks.js
var ListChecks = [
  ["path", { d: "m3 17 2 2 4-4" }],
  ["path", { d: "m3 7 2 2 4-4" }],
  ["path", { d: "M13 6h8" }],
  ["path", { d: "M13 12h8" }],
  ["path", { d: "M13 18h8" }]
];

// node_modules/lucide/dist/esm/icons/list-collapse.js
var ListCollapse = [
  ["path", { d: "m3 10 2.5-2.5L3 5" }],
  ["path", { d: "m3 19 2.5-2.5L3 14" }],
  ["path", { d: "M10 6h11" }],
  ["path", { d: "M10 12h11" }],
  ["path", { d: "M10 18h11" }]
];

// node_modules/lucide/dist/esm/icons/list-end.js
var ListEnd = [
  ["path", { d: "M16 12H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M10 18H3" }],
  ["path", { d: "M21 6v10a2 2 0 0 1-2 2h-5" }],
  ["path", { d: "m16 16-2 2 2 2" }]
];

// node_modules/lucide/dist/esm/icons/list-filter-plus.js
var ListFilterPlus = [
  ["path", { d: "M10 18h4" }],
  ["path", { d: "M11 6H3" }],
  ["path", { d: "M15 6h6" }],
  ["path", { d: "M18 9V3" }],
  ["path", { d: "M7 12h8" }]
];

// node_modules/lucide/dist/esm/icons/list-filter.js
var ListFilter = [
  ["path", { d: "M3 6h18" }],
  ["path", { d: "M7 12h10" }],
  ["path", { d: "M10 18h4" }]
];

// node_modules/lucide/dist/esm/icons/list-minus.js
var ListMinus = [
  ["path", { d: "M11 12H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M16 18H3" }],
  ["path", { d: "M21 12h-6" }]
];

// node_modules/lucide/dist/esm/icons/list-music.js
var ListMusic = [
  ["path", { d: "M21 15V6" }],
  ["path", { d: "M18.5 18a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" }],
  ["path", { d: "M12 12H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M12 18H3" }]
];

// node_modules/lucide/dist/esm/icons/list-plus.js
var ListPlus = [
  ["path", { d: "M11 12H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M16 18H3" }],
  ["path", { d: "M18 9v6" }],
  ["path", { d: "M21 12h-6" }]
];

// node_modules/lucide/dist/esm/icons/list-ordered.js
var ListOrdered = [
  ["path", { d: "M10 12h11" }],
  ["path", { d: "M10 18h11" }],
  ["path", { d: "M10 6h11" }],
  ["path", { d: "M4 10h2" }],
  ["path", { d: "M4 6h1v4" }],
  ["path", { d: "M6 18H4c0-1 2-2 2-3s-1-1.5-2-1" }]
];

// node_modules/lucide/dist/esm/icons/list-restart.js
var ListRestart = [
  ["path", { d: "M21 6H3" }],
  ["path", { d: "M7 12H3" }],
  ["path", { d: "M7 18H3" }],
  ["path", { d: "M12 18a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L11 14" }],
  ["path", { d: "M11 10v4h4" }]
];

// node_modules/lucide/dist/esm/icons/list-start.js
var ListStart = [
  ["path", { d: "M16 12H3" }],
  ["path", { d: "M16 18H3" }],
  ["path", { d: "M10 6H3" }],
  ["path", { d: "M21 18V8a2 2 0 0 0-2-2h-5" }],
  ["path", { d: "m16 8-2-2 2-2" }]
];

// node_modules/lucide/dist/esm/icons/list-todo.js
var ListTodo = [
  ["rect", { x: "3", y: "5", width: "6", height: "6", rx: "1" }],
  ["path", { d: "m3 17 2 2 4-4" }],
  ["path", { d: "M13 6h8" }],
  ["path", { d: "M13 12h8" }],
  ["path", { d: "M13 18h8" }]
];

// node_modules/lucide/dist/esm/icons/list-tree.js
var ListTree = [
  ["path", { d: "M21 12h-8" }],
  ["path", { d: "M21 6H8" }],
  ["path", { d: "M21 18h-8" }],
  ["path", { d: "M3 6v4c0 1.1.9 2 2 2h3" }],
  ["path", { d: "M3 10v6c0 1.1.9 2 2 2h3" }]
];

// node_modules/lucide/dist/esm/icons/list-video.js
var ListVideo = [
  ["path", { d: "M12 12H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M12 18H3" }],
  ["path", { d: "m16 12 5 3-5 3v-6Z" }]
];

// node_modules/lucide/dist/esm/icons/list-x.js
var ListX = [
  ["path", { d: "M11 12H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M16 18H3" }],
  ["path", { d: "m19 10-4 4" }],
  ["path", { d: "m15 10 4 4" }]
];

// node_modules/lucide/dist/esm/icons/list.js
var List = [
  ["path", { d: "M3 12h.01" }],
  ["path", { d: "M3 18h.01" }],
  ["path", { d: "M3 6h.01" }],
  ["path", { d: "M8 12h13" }],
  ["path", { d: "M8 18h13" }],
  ["path", { d: "M8 6h13" }]
];

// node_modules/lucide/dist/esm/icons/loader-circle.js
var LoaderCircle = [["path", { d: "M21 12a9 9 0 1 1-6.219-8.56" }]];

// node_modules/lucide/dist/esm/icons/loader.js
var Loader = [
  ["path", { d: "M12 2v4" }],
  ["path", { d: "m16.2 7.8 2.9-2.9" }],
  ["path", { d: "M18 12h4" }],
  ["path", { d: "m16.2 16.2 2.9 2.9" }],
  ["path", { d: "M12 18v4" }],
  ["path", { d: "m4.9 19.1 2.9-2.9" }],
  ["path", { d: "M2 12h4" }],
  ["path", { d: "m4.9 4.9 2.9 2.9" }]
];

// node_modules/lucide/dist/esm/icons/locate-fixed.js
var LocateFixed = [
  ["line", { x1: "2", x2: "5", y1: "12", y2: "12" }],
  ["line", { x1: "19", x2: "22", y1: "12", y2: "12" }],
  ["line", { x1: "12", x2: "12", y1: "2", y2: "5" }],
  ["line", { x1: "12", x2: "12", y1: "19", y2: "22" }],
  ["circle", { cx: "12", cy: "12", r: "7" }],
  ["circle", { cx: "12", cy: "12", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/loader-pinwheel.js
var LoaderPinwheel = [
  ["path", { d: "M22 12a1 1 0 0 1-10 0 1 1 0 0 0-10 0" }],
  ["path", { d: "M7 20.7a1 1 0 1 1 5-8.7 1 1 0 1 0 5-8.6" }],
  ["path", { d: "M7 3.3a1 1 0 1 1 5 8.6 1 1 0 1 0 5 8.6" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/locate-off.js
var LocateOff = [
  ["path", { d: "M12 19v3" }],
  ["path", { d: "M12 2v3" }],
  ["path", { d: "M18.89 13.24a7 7 0 0 0-8.13-8.13" }],
  ["path", { d: "M19 12h3" }],
  ["path", { d: "M2 12h3" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M7.05 7.05a7 7 0 0 0 9.9 9.9" }]
];

// node_modules/lucide/dist/esm/icons/locate.js
var Locate = [
  ["line", { x1: "2", x2: "5", y1: "12", y2: "12" }],
  ["line", { x1: "19", x2: "22", y1: "12", y2: "12" }],
  ["line", { x1: "12", x2: "12", y1: "2", y2: "5" }],
  ["line", { x1: "12", x2: "12", y1: "19", y2: "22" }],
  ["circle", { cx: "12", cy: "12", r: "7" }]
];

// node_modules/lucide/dist/esm/icons/location-edit.js
var LocationEdit = [
  ["path", { d: "M17.97 9.304A8 8 0 0 0 2 10c0 4.69 4.887 9.562 7.022 11.468" }],
  [
    "path",
    {
      d: "M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ],
  ["circle", { cx: "10", cy: "10", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/lock-keyhole-open.js
var LockKeyholeOpen = [
  ["circle", { cx: "12", cy: "16", r: "1" }],
  ["rect", { width: "18", height: "12", x: "3", y: "10", rx: "2" }],
  ["path", { d: "M7 10V7a5 5 0 0 1 9.33-2.5" }]
];

// node_modules/lucide/dist/esm/icons/lock-keyhole.js
var LockKeyhole = [
  ["circle", { cx: "12", cy: "16", r: "1" }],
  ["rect", { x: "3", y: "10", width: "18", height: "12", rx: "2" }],
  ["path", { d: "M7 10V7a5 5 0 0 1 10 0v3" }]
];

// node_modules/lucide/dist/esm/icons/lock-open.js
var LockOpen = [
  ["rect", { width: "18", height: "11", x: "3", y: "11", rx: "2", ry: "2" }],
  ["path", { d: "M7 11V7a5 5 0 0 1 9.9-1" }]
];

// node_modules/lucide/dist/esm/icons/lock.js
var Lock = [
  ["rect", { width: "18", height: "11", x: "3", y: "11", rx: "2", ry: "2" }],
  ["path", { d: "M7 11V7a5 5 0 0 1 10 0v4" }]
];

// node_modules/lucide/dist/esm/icons/log-in.js
var LogIn = [
  ["path", { d: "M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" }],
  ["polyline", { points: "10 17 15 12 10 7" }],
  ["line", { x1: "15", x2: "3", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/log-out.js
var LogOut = [
  ["path", { d: "M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" }],
  ["polyline", { points: "16 17 21 12 16 7" }],
  ["line", { x1: "21", x2: "9", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/logs.js
var Logs = [
  ["path", { d: "M13 12h8" }],
  ["path", { d: "M13 18h8" }],
  ["path", { d: "M13 6h8" }],
  ["path", { d: "M3 12h1" }],
  ["path", { d: "M3 18h1" }],
  ["path", { d: "M3 6h1" }],
  ["path", { d: "M8 12h1" }],
  ["path", { d: "M8 18h1" }],
  ["path", { d: "M8 6h1" }]
];

// node_modules/lucide/dist/esm/icons/lollipop.js
var Lollipop = [
  ["circle", { cx: "11", cy: "11", r: "8" }],
  ["path", { d: "m21 21-4.3-4.3" }],
  ["path", { d: "M11 11a2 2 0 0 0 4 0 4 4 0 0 0-8 0 6 6 0 0 0 12 0" }]
];

// node_modules/lucide/dist/esm/icons/luggage.js
var Luggage = [
  ["path", { d: "M6 20a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2" }],
  ["path", { d: "M8 18V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v14" }],
  ["path", { d: "M10 20h4" }],
  ["circle", { cx: "16", cy: "20", r: "2" }],
  ["circle", { cx: "8", cy: "20", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/magnet.js
var Magnet = [
  [
    "path",
    {
      d: "m6 15-4-4 6.75-6.77a7.79 7.79 0 0 1 11 11L13 22l-4-4 6.39-6.36a2.14 2.14 0 0 0-3-3L6 15"
    }
  ],
  ["path", { d: "m5 8 4 4" }],
  ["path", { d: "m12 15 4 4" }]
];

// node_modules/lucide/dist/esm/icons/mail-check.js
var MailCheck = [
  ["path", { d: "M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "m16 19 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/mail-minus.js
var MailMinus = [
  ["path", { d: "M22 15V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "M16 19h6" }]
];

// node_modules/lucide/dist/esm/icons/mail-open.js
var MailOpen = [
  [
    "path",
    {
      d: "M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z"
    }
  ],
  ["path", { d: "m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10" }]
];

// node_modules/lucide/dist/esm/icons/mail-plus.js
var MailPlus = [
  ["path", { d: "M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "M19 16v6" }],
  ["path", { d: "M16 19h6" }]
];

// node_modules/lucide/dist/esm/icons/mail-question.js
var MailQuestion = [
  ["path", { d: "M22 10.5V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h12.5" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "M18 15.28c.2-.4.5-.8.9-1a2.1 2.1 0 0 1 2.6.4c.3.4.5.8.5 1.3 0 1.3-2 2-2 2" }],
  ["path", { d: "M20 22v.01" }]
];

// node_modules/lucide/dist/esm/icons/mail-warning.js
var MailWarning = [
  ["path", { d: "M22 10.5V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h12.5" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "M20 14v4" }],
  ["path", { d: "M20 22v.01" }]
];

// node_modules/lucide/dist/esm/icons/mail-search.js
var MailSearch = [
  ["path", { d: "M22 12.5V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h7.5" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "M18 21a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" }],
  ["circle", { cx: "18", cy: "18", r: "3" }],
  ["path", { d: "m22 22-1.5-1.5" }]
];

// node_modules/lucide/dist/esm/icons/mail-x.js
var MailX = [
  ["path", { d: "M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9" }],
  ["path", { d: "m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" }],
  ["path", { d: "m17 17 4 4" }],
  ["path", { d: "m21 17-4 4" }]
];

// node_modules/lucide/dist/esm/icons/mail.js
var Mail = [
  ["path", { d: "m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" }],
  ["rect", { x: "2", y: "4", width: "20", height: "16", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/mailbox.js
var Mailbox = [
  ["path", { d: "M22 17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9.5C2 7 4 5 6.5 5H18c2.2 0 4 1.8 4 4v8Z" }],
  ["polyline", { points: "15,9 18,9 18,11" }],
  ["path", { d: "M6.5 5C9 5 11 7 11 9.5V17a2 2 0 0 1-2 2" }],
  ["line", { x1: "6", x2: "7", y1: "10", y2: "10" }]
];

// node_modules/lucide/dist/esm/icons/mails.js
var Mails = [
  ["rect", { width: "16", height: "13", x: "6", y: "4", rx: "2" }],
  ["path", { d: "m22 7-7.1 3.78c-.57.3-1.23.3-1.8 0L6 7" }],
  ["path", { d: "M2 8v11c0 1.1.9 2 2 2h14" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-check-inside.js
var MapPinCheckInside = [
  [
    "path",
    {
      d: "M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"
    }
  ],
  ["path", { d: "m9 10 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-check.js
var MapPinCheck = [
  [
    "path",
    {
      d: "M19.43 12.935c.357-.967.57-1.955.57-2.935a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0 32.197 32.197 0 0 0 .813-.728"
    }
  ],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "m16 18 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-house.js
var MapPinHouse = [
  [
    "path",
    {
      d: "M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"
    }
  ],
  ["path", { d: "M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2" }],
  ["path", { d: "M18 22v-3" }],
  ["circle", { cx: "10", cy: "10", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-minus-inside.js
var MapPinMinusInside = [
  [
    "path",
    {
      d: "M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"
    }
  ],
  ["path", { d: "M9 10h6" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-minus.js
var MapPinMinus = [
  [
    "path",
    {
      d: "M18.977 14C19.6 12.701 20 11.343 20 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0 32 32 0 0 0 .824-.738"
    }
  ],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "M16 18h6" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-off.js
var MapPinOff = [
  ["path", { d: "M12.75 7.09a3 3 0 0 1 2.16 2.16" }],
  [
    "path",
    {
      d: "M17.072 17.072c-1.634 2.17-3.527 3.912-4.471 4.727a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 1.432-4.568"
    }
  ],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M8.475 2.818A8 8 0 0 1 20 10c0 1.183-.31 2.377-.81 3.533" }],
  ["path", { d: "M9.13 9.13a3 3 0 0 0 3.74 3.74" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-plus-inside.js
var MapPinPlusInside = [
  [
    "path",
    {
      d: "M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"
    }
  ],
  ["path", { d: "M12 7v6" }],
  ["path", { d: "M9 10h6" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-x-inside.js
var MapPinXInside = [
  [
    "path",
    {
      d: "M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"
    }
  ],
  ["path", { d: "m14.5 7.5-5 5" }],
  ["path", { d: "m9.5 7.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-plus.js
var MapPinPlus = [
  [
    "path",
    {
      d: "M19.914 11.105A7.298 7.298 0 0 0 20 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0 32 32 0 0 0 .824-.738"
    }
  ],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "M16 18h6" }],
  ["path", { d: "M19 15v6" }]
];

// node_modules/lucide/dist/esm/icons/map-pin.js
var MapPin = [
  [
    "path",
    {
      d: "M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"
    }
  ],
  ["circle", { cx: "12", cy: "10", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/map-pin-x.js
var MapPinX = [
  [
    "path",
    {
      d: "M19.752 11.901A7.78 7.78 0 0 0 20 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0 19 19 0 0 0 .09-.077"
    }
  ],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "m21.5 15.5-5 5" }],
  ["path", { d: "m21.5 20.5-5-5" }]
];

// node_modules/lucide/dist/esm/icons/map-pinned.js
var MapPinned = [
  [
    "path",
    {
      d: "M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"
    }
  ],
  ["circle", { cx: "12", cy: "8", r: "2" }],
  [
    "path",
    {
      d: "M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/map-plus.js
var MapPlus = [
  [
    "path",
    {
      d: "m11 19-1.106-.552a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0l4.212 2.106a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619V12"
    }
  ],
  ["path", { d: "M15 5.764V12" }],
  ["path", { d: "M18 15v6" }],
  ["path", { d: "M21 18h-6" }],
  ["path", { d: "M9 3.236v15" }]
];

// node_modules/lucide/dist/esm/icons/map.js
var Map = [
  [
    "path",
    {
      d: "M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"
    }
  ],
  ["path", { d: "M15 5.764v15" }],
  ["path", { d: "M9 3.236v15" }]
];

// node_modules/lucide/dist/esm/icons/mars-stroke.js
var MarsStroke = [
  ["path", { d: "m14 6 4 4" }],
  ["path", { d: "M17 3h4v4" }],
  ["path", { d: "m21 3-7.75 7.75" }],
  ["circle", { cx: "9", cy: "15", r: "6" }]
];

// node_modules/lucide/dist/esm/icons/mars.js
var Mars = [
  ["path", { d: "M16 3h5v5" }],
  ["path", { d: "m21 3-6.75 6.75" }],
  ["circle", { cx: "10", cy: "14", r: "6" }]
];

// node_modules/lucide/dist/esm/icons/martini.js
var Martini = [
  ["path", { d: "M8 22h8" }],
  ["path", { d: "M12 11v11" }],
  ["path", { d: "m19 3-7 8-7-8Z" }]
];

// node_modules/lucide/dist/esm/icons/maximize-2.js
var Maximize2 = [
  ["polyline", { points: "15 3 21 3 21 9" }],
  ["polyline", { points: "9 21 3 21 3 15" }],
  ["line", { x1: "21", x2: "14", y1: "3", y2: "10" }],
  ["line", { x1: "3", x2: "10", y1: "21", y2: "14" }]
];

// node_modules/lucide/dist/esm/icons/maximize.js
var Maximize = [
  ["path", { d: "M8 3H5a2 2 0 0 0-2 2v3" }],
  ["path", { d: "M21 8V5a2 2 0 0 0-2-2h-3" }],
  ["path", { d: "M3 16v3a2 2 0 0 0 2 2h3" }],
  ["path", { d: "M16 21h3a2 2 0 0 0 2-2v-3" }]
];

// node_modules/lucide/dist/esm/icons/medal.js
var Medal = [
  [
    "path",
    {
      d: "M7.21 15 2.66 7.14a2 2 0 0 1 .13-2.2L4.4 2.8A2 2 0 0 1 6 2h12a2 2 0 0 1 1.6.8l1.6 2.14a2 2 0 0 1 .14 2.2L16.79 15"
    }
  ],
  ["path", { d: "M11 12 5.12 2.2" }],
  ["path", { d: "m13 12 5.88-9.8" }],
  ["path", { d: "M8 7h8" }],
  ["circle", { cx: "12", cy: "17", r: "5" }],
  ["path", { d: "M12 18v-2h-.5" }]
];

// node_modules/lucide/dist/esm/icons/megaphone-off.js
var MegaphoneOff = [
  ["path", { d: "M9.26 9.26 3 11v3l14.14 3.14" }],
  ["path", { d: "M21 15.34V6l-7.31 2.03" }],
  ["path", { d: "M11.6 16.8a3 3 0 1 1-5.8-1.6" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/meh.js
var Meh = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["line", { x1: "8", x2: "16", y1: "15", y2: "15" }],
  ["line", { x1: "9", x2: "9.01", y1: "9", y2: "9" }],
  ["line", { x1: "15", x2: "15.01", y1: "9", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/megaphone.js
var Megaphone = [
  ["path", { d: "m3 11 18-5v12L3 14v-3z" }],
  ["path", { d: "M11.6 16.8a3 3 0 1 1-5.8-1.6" }]
];

// node_modules/lucide/dist/esm/icons/memory-stick.js
var MemoryStick = [
  ["path", { d: "M6 19v-3" }],
  ["path", { d: "M10 19v-3" }],
  ["path", { d: "M14 19v-3" }],
  ["path", { d: "M18 19v-3" }],
  ["path", { d: "M8 11V9" }],
  ["path", { d: "M16 11V9" }],
  ["path", { d: "M12 11V9" }],
  ["path", { d: "M2 15h20" }],
  [
    "path",
    {
      d: "M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1.1a2 2 0 0 0 0 3.837V17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5.1a2 2 0 0 0 0-3.837Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/menu.js
var Menu = [
  ["path", { d: "M4 12h16" }],
  ["path", { d: "M4 18h16" }],
  ["path", { d: "M4 6h16" }]
];

// node_modules/lucide/dist/esm/icons/merge.js
var Merge = [
  ["path", { d: "m8 6 4-4 4 4" }],
  ["path", { d: "M12 2v10.3a4 4 0 0 1-1.172 2.872L4 22" }],
  ["path", { d: "m20 22-5-5" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-code.js
var MessageCircleCode = [
  ["path", { d: "M10 9.5 8 12l2 2.5" }],
  ["path", { d: "m14 9.5 2 2.5-2 2.5" }],
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22z" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-dashed.js
var MessageCircleDashed = [
  ["path", { d: "M13.5 3.1c-.5 0-1-.1-1.5-.1s-1 .1-1.5.1" }],
  ["path", { d: "M19.3 6.8a10.45 10.45 0 0 0-2.1-2.1" }],
  ["path", { d: "M20.9 13.5c.1-.5.1-1 .1-1.5s-.1-1-.1-1.5" }],
  ["path", { d: "M17.2 19.3a10.45 10.45 0 0 0 2.1-2.1" }],
  ["path", { d: "M10.5 20.9c.5.1 1 .1 1.5.1s1-.1 1.5-.1" }],
  ["path", { d: "M3.5 17.5 2 22l4.5-1.5" }],
  ["path", { d: "M3.1 10.5c0 .5-.1 1-.1 1.5s.1 1 .1 1.5" }],
  ["path", { d: "M6.8 4.7a10.45 10.45 0 0 0-2.1 2.1" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-heart.js
var MessageCircleHeart = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  [
    "path",
    {
      d: "M15.8 9.2a2.5 2.5 0 0 0-3.5 0l-.3.4-.35-.3a2.42 2.42 0 1 0-3.2 3.6l3.6 3.5 3.6-3.5c1.2-1.2 1.1-2.7.2-3.7"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/message-circle-more.js
var MessageCircleMore = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  ["path", { d: "M8 12h.01" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M16 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-plus.js
var MessageCirclePlus = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "M12 8v8" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-question.js
var MessageCircleQuestion = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  ["path", { d: "M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" }],
  ["path", { d: "M12 17h.01" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-off.js
var MessageCircleOff = [
  ["path", { d: "M20.5 14.9A9 9 0 0 0 9.1 3.5" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M5.6 5.6C3 8.3 2.2 12.5 4 16l-2 6 6-2c3.4 1.8 7.6 1.1 10.3-1.7" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-reply.js
var MessageCircleReply = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  ["path", { d: "m10 15-3-3 3-3" }],
  ["path", { d: "M7 12h7a2 2 0 0 1 2 2v1" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-warning.js
var MessageCircleWarning = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  ["path", { d: "M12 8v4" }],
  ["path", { d: "M12 16h.01" }]
];

// node_modules/lucide/dist/esm/icons/message-circle-x.js
var MessageCircleX = [
  ["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "m9 9 6 6" }]
];

// node_modules/lucide/dist/esm/icons/message-circle.js
var MessageCircle = [["path", { d: "M7.9 20A9 9 0 1 0 4 16.1L2 22Z" }]];

// node_modules/lucide/dist/esm/icons/message-square-code.js
var MessageSquareCode = [
  ["path", { d: "M10 7.5 8 10l2 2.5" }],
  ["path", { d: "m14 7.5 2 2.5-2 2.5" }],
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }]
];

// node_modules/lucide/dist/esm/icons/message-square-dashed.js
var MessageSquareDashed = [
  ["path", { d: "M10 17H7l-4 4v-7" }],
  ["path", { d: "M14 17h1" }],
  ["path", { d: "M14 3h1" }],
  ["path", { d: "M19 3a2 2 0 0 1 2 2" }],
  ["path", { d: "M21 14v1a2 2 0 0 1-2 2" }],
  ["path", { d: "M21 9v1" }],
  ["path", { d: "M3 9v1" }],
  ["path", { d: "M5 3a2 2 0 0 0-2 2" }],
  ["path", { d: "M9 3h1" }]
];

// node_modules/lucide/dist/esm/icons/message-square-dot.js
var MessageSquareDot = [
  ["path", { d: "M11.7 3H5a2 2 0 0 0-2 2v16l4-4h12a2 2 0 0 0 2-2v-2.7" }],
  ["circle", { cx: "18", cy: "6", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/message-square-heart.js
var MessageSquareHeart = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  [
    "path",
    {
      d: "M14.8 7.5a1.84 1.84 0 0 0-2.6 0l-.2.3-.3-.3a1.84 1.84 0 1 0-2.4 2.8L12 13l2.7-2.7c.9-.9.8-2.1.1-2.8"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/message-square-diff.js
var MessageSquareDiff = [
  ["path", { d: "m5 19-2 2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2" }],
  ["path", { d: "M9 10h6" }],
  ["path", { d: "M12 7v6" }],
  ["path", { d: "M9 17h6" }]
];

// node_modules/lucide/dist/esm/icons/message-square-lock.js
var MessageSquareLock = [
  ["path", { d: "M19 15v-2a2 2 0 1 0-4 0v2" }],
  ["path", { d: "M9 17H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3.5" }],
  ["rect", { x: "13", y: "15", width: "8", height: "5", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/message-square-more.js
var MessageSquareMore = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "M8 10h.01" }],
  ["path", { d: "M12 10h.01" }],
  ["path", { d: "M16 10h.01" }]
];

// node_modules/lucide/dist/esm/icons/message-square-off.js
var MessageSquareOff = [
  ["path", { d: "M21 15V5a2 2 0 0 0-2-2H9" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M3.6 3.6c-.4.3-.6.8-.6 1.4v16l4-4h10" }]
];

// node_modules/lucide/dist/esm/icons/message-square-plus.js
var MessageSquarePlus = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "M12 7v6" }],
  ["path", { d: "M9 10h6" }]
];

// node_modules/lucide/dist/esm/icons/message-square-quote.js
var MessageSquareQuote = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "M8 12a2 2 0 0 0 2-2V8H8" }],
  ["path", { d: "M14 12a2 2 0 0 0 2-2V8h-2" }]
];

// node_modules/lucide/dist/esm/icons/message-square-share.js
var MessageSquareShare = [
  ["path", { d: "M21 12v3a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h7" }],
  ["path", { d: "M16 3h5v5" }],
  ["path", { d: "m16 8 5-5" }]
];

// node_modules/lucide/dist/esm/icons/message-square-reply.js
var MessageSquareReply = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "m10 7-3 3 3 3" }],
  ["path", { d: "M17 13v-1a2 2 0 0 0-2-2H7" }]
];

// node_modules/lucide/dist/esm/icons/message-square-text.js
var MessageSquareText = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "M13 8H7" }],
  ["path", { d: "M17 12H7" }]
];

// node_modules/lucide/dist/esm/icons/message-square-warning.js
var MessageSquareWarning = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "M12 7v2" }],
  ["path", { d: "M12 13h.01" }]
];

// node_modules/lucide/dist/esm/icons/message-square-x.js
var MessageSquareX = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }],
  ["path", { d: "m14.5 7.5-5 5" }],
  ["path", { d: "m9.5 7.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/message-square.js
var MessageSquare = [
  ["path", { d: "M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" }]
];

// node_modules/lucide/dist/esm/icons/messages-square.js
var MessagesSquare = [
  ["path", { d: "M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z" }],
  ["path", { d: "M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1" }]
];

// node_modules/lucide/dist/esm/icons/mic-off.js
var MicOff = [
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }],
  ["path", { d: "M18.89 13.23A7.12 7.12 0 0 0 19 12v-2" }],
  ["path", { d: "M5 10v2a7 7 0 0 0 12 5" }],
  ["path", { d: "M15 9.34V5a3 3 0 0 0-5.68-1.33" }],
  ["path", { d: "M9 9v3a3 3 0 0 0 5.12 2.12" }],
  ["line", { x1: "12", x2: "12", y1: "19", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/mic-vocal.js
var MicVocal = [
  ["path", { d: "m11 7.601-5.994 8.19a1 1 0 0 0 .1 1.298l.817.818a1 1 0 0 0 1.314.087L15.09 12" }],
  [
    "path",
    {
      d: "M16.5 21.174C15.5 20.5 14.372 20 13 20c-2.058 0-3.928 2.356-6 2-2.072-.356-2.775-3.369-1.5-4.5"
    }
  ],
  ["circle", { cx: "16", cy: "7", r: "5" }]
];

// node_modules/lucide/dist/esm/icons/mic.js
var Mic = [
  ["path", { d: "M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" }],
  ["path", { d: "M19 10v2a7 7 0 0 1-14 0v-2" }],
  ["line", { x1: "12", x2: "12", y1: "19", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/microchip.js
var Microchip = [
  ["path", { d: "M18 12h2" }],
  ["path", { d: "M18 16h2" }],
  ["path", { d: "M18 20h2" }],
  ["path", { d: "M18 4h2" }],
  ["path", { d: "M18 8h2" }],
  ["path", { d: "M4 12h2" }],
  ["path", { d: "M4 16h2" }],
  ["path", { d: "M4 20h2" }],
  ["path", { d: "M4 4h2" }],
  ["path", { d: "M4 8h2" }],
  [
    "path",
    {
      d: "M8 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-1.5c-.276 0-.494.227-.562.495a2 2 0 0 1-3.876 0C9.994 2.227 9.776 2 9.5 2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/microwave.js
var Microwave = [
  ["rect", { width: "20", height: "15", x: "2", y: "4", rx: "2" }],
  ["rect", { width: "8", height: "7", x: "6", y: "8", rx: "1" }],
  ["path", { d: "M18 8v7" }],
  ["path", { d: "M6 19v2" }],
  ["path", { d: "M18 19v2" }]
];

// node_modules/lucide/dist/esm/icons/milestone.js
var Milestone = [
  ["path", { d: "M12 13v8" }],
  ["path", { d: "M12 3v3" }],
  [
    "path",
    {
      d: "M4 6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h13a2 2 0 0 0 1.152-.365l3.424-2.317a1 1 0 0 0 0-1.635l-3.424-2.318A2 2 0 0 0 17 6z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/microscope.js
var Microscope = [
  ["path", { d: "M6 18h8" }],
  ["path", { d: "M3 22h18" }],
  ["path", { d: "M14 22a7 7 0 1 0 0-14h-1" }],
  ["path", { d: "M9 14h2" }],
  ["path", { d: "M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z" }],
  ["path", { d: "M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3" }]
];

// node_modules/lucide/dist/esm/icons/milk-off.js
var MilkOff = [
  ["path", { d: "M8 2h8" }],
  [
    "path",
    {
      d: "M9 2v1.343M15 2v2.789a4 4 0 0 0 .672 2.219l.656.984a4 4 0 0 1 .672 2.22v1.131M7.8 7.8l-.128.192A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-3"
    }
  ],
  ["path", { d: "M7 15a6.47 6.47 0 0 1 5 0 6.472 6.472 0 0 0 3.435.435" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/milk.js
var Milk = [
  ["path", { d: "M8 2h8" }],
  [
    "path",
    {
      d: "M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"
    }
  ],
  ["path", { d: "M7 15a6.472 6.472 0 0 1 5 0 6.47 6.47 0 0 0 5 0" }]
];

// node_modules/lucide/dist/esm/icons/minimize-2.js
var Minimize2 = [
  ["polyline", { points: "4 14 10 14 10 20" }],
  ["polyline", { points: "20 10 14 10 14 4" }],
  ["line", { x1: "14", x2: "21", y1: "10", y2: "3" }],
  ["line", { x1: "3", x2: "10", y1: "21", y2: "14" }]
];

// node_modules/lucide/dist/esm/icons/minimize.js
var Minimize = [
  ["path", { d: "M8 3v3a2 2 0 0 1-2 2H3" }],
  ["path", { d: "M21 8h-3a2 2 0 0 1-2-2V3" }],
  ["path", { d: "M3 16h3a2 2 0 0 1 2 2v3" }],
  ["path", { d: "M16 21v-3a2 2 0 0 1 2-2h3" }]
];

// node_modules/lucide/dist/esm/icons/monitor-check.js
var MonitorCheck = [
  ["path", { d: "m9 10 2 2 4-4" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }]
];

// node_modules/lucide/dist/esm/icons/minus.js
var Minus = [["path", { d: "M5 12h14" }]];

// node_modules/lucide/dist/esm/icons/monitor-cog.js
var MonitorCog = [
  ["path", { d: "M12 17v4" }],
  ["path", { d: "m14.305 7.53.923-.382" }],
  ["path", { d: "m15.228 4.852-.923-.383" }],
  ["path", { d: "m16.852 3.228-.383-.924" }],
  ["path", { d: "m16.852 8.772-.383.923" }],
  ["path", { d: "m19.148 3.228.383-.924" }],
  ["path", { d: "m19.53 9.696-.382-.924" }],
  ["path", { d: "m20.772 4.852.924-.383" }],
  ["path", { d: "m20.772 7.148.924.383" }],
  ["path", { d: "M22 13v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7" }],
  ["path", { d: "M8 21h8" }],
  ["circle", { cx: "18", cy: "6", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/monitor-dot.js
var MonitorDot = [
  ["circle", { cx: "19", cy: "6", r: "3" }],
  ["path", { d: "M22 12v3a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }]
];

// node_modules/lucide/dist/esm/icons/monitor-down.js
var MonitorDown = [
  ["path", { d: "M12 13V7" }],
  ["path", { d: "m15 10-3 3-3-3" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }]
];

// node_modules/lucide/dist/esm/icons/monitor-off.js
var MonitorOff = [
  ["path", { d: "M17 17H4a2 2 0 0 1-2-2V5c0-1.5 1-2 1-2" }],
  ["path", { d: "M22 15V5a2 2 0 0 0-2-2H9" }],
  ["path", { d: "M8 21h8" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/monitor-pause.js
var MonitorPause = [
  ["path", { d: "M10 13V7" }],
  ["path", { d: "M14 13V7" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }]
];

// node_modules/lucide/dist/esm/icons/monitor-play.js
var MonitorPlay = [
  [
    "path",
    {
      d: "M10 7.75a.75.75 0 0 1 1.142-.638l3.664 2.249a.75.75 0 0 1 0 1.278l-3.664 2.25a.75.75 0 0 1-1.142-.64z"
    }
  ],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }],
  ["rect", { x: "2", y: "3", width: "20", height: "14", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/monitor-speaker.js
var MonitorSpeaker = [
  ["path", { d: "M5.5 20H8" }],
  ["path", { d: "M17 9h.01" }],
  ["rect", { width: "10", height: "16", x: "12", y: "4", rx: "2" }],
  ["path", { d: "M8 6H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4" }],
  ["circle", { cx: "17", cy: "15", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/monitor-smartphone.js
var MonitorSmartphone = [
  ["path", { d: "M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8" }],
  ["path", { d: "M10 19v-3.96 3.15" }],
  ["path", { d: "M7 19h5" }],
  ["rect", { width: "6", height: "10", x: "16", y: "12", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/monitor-stop.js
var MonitorStop = [
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }],
  ["rect", { x: "2", y: "3", width: "20", height: "14", rx: "2" }],
  ["rect", { x: "9", y: "7", width: "6", height: "6", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/monitor-up.js
var MonitorUp = [
  ["path", { d: "m9 10 3-3 3 3" }],
  ["path", { d: "M12 13V7" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }]
];

// node_modules/lucide/dist/esm/icons/monitor-x.js
var MonitorX = [
  ["path", { d: "m14.5 12.5-5-5" }],
  ["path", { d: "m9.5 12.5 5-5" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "M8 21h8" }]
];

// node_modules/lucide/dist/esm/icons/monitor.js
var Monitor = [
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }],
  ["line", { x1: "8", x2: "16", y1: "21", y2: "21" }],
  ["line", { x1: "12", x2: "12", y1: "17", y2: "21" }]
];

// node_modules/lucide/dist/esm/icons/moon-star.js
var MoonStar = [
  ["path", { d: "M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9" }],
  ["path", { d: "M20 3v4" }],
  ["path", { d: "M22 5h-4" }]
];

// node_modules/lucide/dist/esm/icons/mountain-snow.js
var MountainSnow = [
  ["path", { d: "m8 3 4 8 5-5 5 15H2L8 3z" }],
  ["path", { d: "M4.14 15.08c2.62-1.57 5.24-1.43 7.86.42 2.74 1.94 5.49 2 8.23.19" }]
];

// node_modules/lucide/dist/esm/icons/moon.js
var Moon = [["path", { d: "M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" }]];

// node_modules/lucide/dist/esm/icons/mountain.js
var Mountain = [["path", { d: "m8 3 4 8 5-5 5 15H2L8 3z" }]];

// node_modules/lucide/dist/esm/icons/mouse-off.js
var MouseOff = [
  ["path", { d: "M12 6v.343" }],
  ["path", { d: "M18.218 18.218A7 7 0 0 1 5 15V9a7 7 0 0 1 .782-3.218" }],
  ["path", { d: "M19 13.343V9A7 7 0 0 0 8.56 2.902" }],
  ["path", { d: "M22 22 2 2" }]
];

// node_modules/lucide/dist/esm/icons/mouse-pointer-2.js
var MousePointer2 = [
  [
    "path",
    {
      d: "M4.037 4.688a.495.495 0 0 1 .651-.651l16 6.5a.5.5 0 0 1-.063.947l-6.124 1.58a2 2 0 0 0-1.438 1.435l-1.579 6.126a.5.5 0 0 1-.947.063z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/mouse-pointer-ban.js
var MousePointerBan = [
  [
    "path",
    {
      d: "M2.034 2.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.944L8.204 7.545a1 1 0 0 0-.66.66l-1.066 3.443a.5.5 0 0 1-.944.033z"
    }
  ],
  ["circle", { cx: "16", cy: "16", r: "6" }],
  ["path", { d: "m11.8 11.8 8.4 8.4" }]
];

// node_modules/lucide/dist/esm/icons/mouse-pointer-click.js
var MousePointerClick = [
  ["path", { d: "M14 4.1 12 6" }],
  ["path", { d: "m5.1 8-2.9-.8" }],
  ["path", { d: "m6 12-1.9 2" }],
  ["path", { d: "M7.2 2.2 8 5.1" }],
  [
    "path",
    {
      d: "M9.037 9.69a.498.498 0 0 1 .653-.653l11 4.5a.5.5 0 0 1-.074.949l-4.349 1.041a1 1 0 0 0-.74.739l-1.04 4.35a.5.5 0 0 1-.95.074z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/mouse-pointer.js
var MousePointer = [
  ["path", { d: "M12.586 12.586 19 19" }],
  [
    "path",
    {
      d: "M3.688 3.037a.497.497 0 0 0-.651.651l6.5 15.999a.501.501 0 0 0 .947-.062l1.569-6.083a2 2 0 0 1 1.448-1.479l6.124-1.579a.5.5 0 0 0 .063-.947z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/mouse.js
var Mouse = [
  ["rect", { x: "5", y: "2", width: "14", height: "20", rx: "7" }],
  ["path", { d: "M12 6v4" }]
];

// node_modules/lucide/dist/esm/icons/move-3d.js
var Move3d = [
  ["path", { d: "M5 3v16h16" }],
  ["path", { d: "m5 19 6-6" }],
  ["path", { d: "m2 6 3-3 3 3" }],
  ["path", { d: "m18 16 3 3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/move-diagonal-2.js
var MoveDiagonal2 = [
  ["path", { d: "M19 13v6h-6" }],
  ["path", { d: "M5 11V5h6" }],
  ["path", { d: "m5 5 14 14" }]
];

// node_modules/lucide/dist/esm/icons/move-diagonal.js
var MoveDiagonal = [
  ["path", { d: "M11 19H5v-6" }],
  ["path", { d: "M13 5h6v6" }],
  ["path", { d: "M19 5 5 19" }]
];

// node_modules/lucide/dist/esm/icons/move-down-left.js
var MoveDownLeft = [
  ["path", { d: "M11 19H5V13" }],
  ["path", { d: "M19 5L5 19" }]
];

// node_modules/lucide/dist/esm/icons/move-down-right.js
var MoveDownRight = [
  ["path", { d: "M19 13V19H13" }],
  ["path", { d: "M5 5L19 19" }]
];

// node_modules/lucide/dist/esm/icons/move-down.js
var MoveDown = [
  ["path", { d: "M8 18L12 22L16 18" }],
  ["path", { d: "M12 2V22" }]
];

// node_modules/lucide/dist/esm/icons/move-horizontal.js
var MoveHorizontal = [
  ["path", { d: "m18 8 4 4-4 4" }],
  ["path", { d: "M2 12h20" }],
  ["path", { d: "m6 8-4 4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/move-left.js
var MoveLeft = [
  ["path", { d: "M6 8L2 12L6 16" }],
  ["path", { d: "M2 12H22" }]
];

// node_modules/lucide/dist/esm/icons/move-right.js
var MoveRight = [
  ["path", { d: "M18 8L22 12L18 16" }],
  ["path", { d: "M2 12H22" }]
];

// node_modules/lucide/dist/esm/icons/move-up-left.js
var MoveUpLeft = [
  ["path", { d: "M5 11V5H11" }],
  ["path", { d: "M5 5L19 19" }]
];

// node_modules/lucide/dist/esm/icons/move-up.js
var MoveUp = [
  ["path", { d: "M8 6L12 2L16 6" }],
  ["path", { d: "M12 2V22" }]
];

// node_modules/lucide/dist/esm/icons/move-up-right.js
var MoveUpRight = [
  ["path", { d: "M13 5H19V11" }],
  ["path", { d: "M19 5L5 19" }]
];

// node_modules/lucide/dist/esm/icons/move-vertical.js
var MoveVertical = [
  ["path", { d: "M12 2v20" }],
  ["path", { d: "m8 18 4 4 4-4" }],
  ["path", { d: "m8 6 4-4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/move.js
var Move = [
  ["path", { d: "M12 2v20" }],
  ["path", { d: "m15 19-3 3-3-3" }],
  ["path", { d: "m19 9 3 3-3 3" }],
  ["path", { d: "M2 12h20" }],
  ["path", { d: "m5 9-3 3 3 3" }],
  ["path", { d: "m9 5 3-3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/music-2.js
var Music2 = [
  ["circle", { cx: "8", cy: "18", r: "4" }],
  ["path", { d: "M12 18V2l7 4" }]
];

// node_modules/lucide/dist/esm/icons/music-3.js
var Music3 = [
  ["circle", { cx: "12", cy: "18", r: "4" }],
  ["path", { d: "M16 18V2" }]
];

// node_modules/lucide/dist/esm/icons/music-4.js
var Music4 = [
  ["path", { d: "M9 18V5l12-2v13" }],
  ["path", { d: "m9 9 12-2" }],
  ["circle", { cx: "6", cy: "18", r: "3" }],
  ["circle", { cx: "18", cy: "16", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/music.js
var Music = [
  ["path", { d: "M9 18V5l12-2v13" }],
  ["circle", { cx: "6", cy: "18", r: "3" }],
  ["circle", { cx: "18", cy: "16", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/navigation-2-off.js
var Navigation2Off = [
  ["path", { d: "M9.31 9.31 5 21l7-4 7 4-1.17-3.17" }],
  ["path", { d: "M14.53 8.88 12 2l-1.17 3.17" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/navigation-2.js
var Navigation2 = [["polygon", { points: "12 2 19 21 12 17 5 21 12 2" }]];

// node_modules/lucide/dist/esm/icons/navigation-off.js
var NavigationOff = [
  ["path", { d: "M8.43 8.43 3 11l8 2 2 8 2.57-5.43" }],
  ["path", { d: "M17.39 11.73 22 2l-9.73 4.61" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/navigation.js
var Navigation = [["polygon", { points: "3 11 22 2 13 21 11 13 3 11" }]];

// node_modules/lucide/dist/esm/icons/network.js
var Network = [
  ["rect", { x: "16", y: "16", width: "6", height: "6", rx: "1" }],
  ["rect", { x: "2", y: "16", width: "6", height: "6", rx: "1" }],
  ["rect", { x: "9", y: "2", width: "6", height: "6", rx: "1" }],
  ["path", { d: "M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" }],
  ["path", { d: "M12 12V8" }]
];

// node_modules/lucide/dist/esm/icons/newspaper.js
var Newspaper = [
  ["path", { d: "M15 18h-5" }],
  ["path", { d: "M18 14h-8" }],
  [
    "path",
    {
      d: "M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"
    }
  ],
  ["rect", { width: "8", height: "4", x: "10", y: "6", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/nfc.js
var Nfc = [
  ["path", { d: "M6 8.32a7.43 7.43 0 0 1 0 7.36" }],
  ["path", { d: "M9.46 6.21a11.76 11.76 0 0 1 0 11.58" }],
  ["path", { d: "M12.91 4.1a15.91 15.91 0 0 1 .01 15.8" }],
  ["path", { d: "M16.37 2a20.16 20.16 0 0 1 0 20" }]
];

// node_modules/lucide/dist/esm/icons/non-binary.js
var NonBinary = [
  ["path", { d: "M12 2v10" }],
  ["path", { d: "m8.5 4 7 4" }],
  ["path", { d: "m8.5 8 7-4" }],
  ["circle", { cx: "12", cy: "17", r: "5" }]
];

// node_modules/lucide/dist/esm/icons/notebook-pen.js
var NotebookPen = [
  ["path", { d: "M13.4 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.4" }],
  ["path", { d: "M2 6h4" }],
  ["path", { d: "M2 10h4" }],
  ["path", { d: "M2 14h4" }],
  ["path", { d: "M2 18h4" }],
  [
    "path",
    {
      d: "M21.378 5.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/notebook-tabs.js
var NotebookTabs = [
  ["path", { d: "M2 6h4" }],
  ["path", { d: "M2 10h4" }],
  ["path", { d: "M2 14h4" }],
  ["path", { d: "M2 18h4" }],
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2" }],
  ["path", { d: "M15 2v20" }],
  ["path", { d: "M15 7h5" }],
  ["path", { d: "M15 12h5" }],
  ["path", { d: "M15 17h5" }]
];

// node_modules/lucide/dist/esm/icons/notebook.js
var Notebook = [
  ["path", { d: "M2 6h4" }],
  ["path", { d: "M2 10h4" }],
  ["path", { d: "M2 14h4" }],
  ["path", { d: "M2 18h4" }],
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2" }],
  ["path", { d: "M16 2v20" }]
];

// node_modules/lucide/dist/esm/icons/notebook-text.js
var NotebookText = [
  ["path", { d: "M2 6h4" }],
  ["path", { d: "M2 10h4" }],
  ["path", { d: "M2 14h4" }],
  ["path", { d: "M2 18h4" }],
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2" }],
  ["path", { d: "M9.5 8h5" }],
  ["path", { d: "M9.5 12H16" }],
  ["path", { d: "M9.5 16H14" }]
];

// node_modules/lucide/dist/esm/icons/notepad-text-dashed.js
var NotepadTextDashed = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M12 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["path", { d: "M16 4h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M20 12v2" }],
  ["path", { d: "M20 18v2a2 2 0 0 1-2 2h-1" }],
  ["path", { d: "M13 22h-2" }],
  ["path", { d: "M7 22H6a2 2 0 0 1-2-2v-2" }],
  ["path", { d: "M4 14v-2" }],
  ["path", { d: "M4 8V6a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M8 10h6" }],
  ["path", { d: "M8 14h8" }],
  ["path", { d: "M8 18h5" }]
];

// node_modules/lucide/dist/esm/icons/notepad-text.js
var NotepadText = [
  ["path", { d: "M8 2v4" }],
  ["path", { d: "M12 2v4" }],
  ["path", { d: "M16 2v4" }],
  ["rect", { width: "16", height: "18", x: "4", y: "4", rx: "2" }],
  ["path", { d: "M8 10h6" }],
  ["path", { d: "M8 14h8" }],
  ["path", { d: "M8 18h5" }]
];

// node_modules/lucide/dist/esm/icons/nut-off.js
var NutOff = [
  ["path", { d: "M12 4V2" }],
  [
    "path",
    {
      d: "M5 10v4a7.004 7.004 0 0 0 5.277 6.787c.412.104.802.292 1.102.592L12 22l.621-.621c.3-.3.69-.488 1.102-.592a7.01 7.01 0 0 0 4.125-2.939"
    }
  ],
  ["path", { d: "M19 10v3.343" }],
  [
    "path",
    {
      d: "M12 12c-1.349-.573-1.905-1.005-2.5-2-.546.902-1.048 1.353-2.5 2-1.018-.644-1.46-1.08-2-2-1.028.71-1.69.918-3 1 1.081-1.048 1.757-2.03 2-3 .194-.776.84-1.551 1.79-2.21m11.654 5.997c.887-.457 1.28-.891 1.556-1.787 1.032.916 1.683 1.157 3 1-1.297-1.036-1.758-2.03-2-3-.5-2-4-4-8-4-.74 0-1.461.068-2.15.192"
    }
  ],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/nut.js
var Nut = [
  ["path", { d: "M12 4V2" }],
  [
    "path",
    {
      d: "M5 10v4a7.004 7.004 0 0 0 5.277 6.787c.412.104.802.292 1.102.592L12 22l.621-.621c.3-.3.69-.488 1.102-.592A7.003 7.003 0 0 0 19 14v-4"
    }
  ],
  [
    "path",
    {
      d: "M12 4C8 4 4.5 6 4 8c-.243.97-.919 1.952-2 3 1.31-.082 1.972-.29 3-1 .54.92.982 1.356 2 2 1.452-.647 1.954-1.098 2.5-2 .595.995 1.151 1.427 2.5 2 1.31-.621 1.862-1.058 2.5-2 .629.977 1.162 1.423 2.5 2 1.209-.548 1.68-.967 2-2 1.032.916 1.683 1.157 3 1-1.297-1.036-1.758-2.03-2-3-.5-2-4-4-8-4Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/octagon-alert.js
var OctagonAlert = [
  ["path", { d: "M12 16h.01" }],
  ["path", { d: "M12 8v4" }],
  [
    "path",
    {
      d: "M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/octagon-minus.js
var OctagonMinus = [
  [
    "path",
    {
      d: "M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z"
    }
  ],
  ["path", { d: "M8 12h8" }]
];

// node_modules/lucide/dist/esm/icons/octagon-pause.js
var OctagonPause = [
  ["path", { d: "M10 15V9" }],
  ["path", { d: "M14 15V9" }],
  [
    "path",
    {
      d: "M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/octagon.js
var Octagon = [
  [
    "path",
    {
      d: "M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/octagon-x.js
var OctagonX = [
  ["path", { d: "m15 9-6 6" }],
  [
    "path",
    {
      d: "M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z"
    }
  ],
  ["path", { d: "m9 9 6 6" }]
];

// node_modules/lucide/dist/esm/icons/omega.js
var Omega = [
  [
    "path",
    {
      d: "M3 20h4.5a.5.5 0 0 0 .5-.5v-.282a.52.52 0 0 0-.247-.437 8 8 0 1 1 8.494-.001.52.52 0 0 0-.247.438v.282a.5.5 0 0 0 .5.5H21"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/option.js
var Option = [
  ["path", { d: "M3 3h6l6 18h6" }],
  ["path", { d: "M14 3h7" }]
];

// node_modules/lucide/dist/esm/icons/orbit.js
var Orbit = [
  ["path", { d: "M20.341 6.484A10 10 0 0 1 10.266 21.85" }],
  ["path", { d: "M3.659 17.516A10 10 0 0 1 13.74 2.152" }],
  ["circle", { cx: "12", cy: "12", r: "3" }],
  ["circle", { cx: "19", cy: "5", r: "2" }],
  ["circle", { cx: "5", cy: "19", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/origami.js
var Origami = [
  ["path", { d: "M12 12V4a1 1 0 0 1 1-1h6.297a1 1 0 0 1 .651 1.759l-4.696 4.025" }],
  [
    "path",
    { d: "m12 21-7.414-7.414A2 2 0 0 1 4 12.172V6.415a1.002 1.002 0 0 1 1.707-.707L20 20.009" }
  ],
  [
    "path",
    {
      d: "m12.214 3.381 8.414 14.966a1 1 0 0 1-.167 1.199l-1.168 1.163a1 1 0 0 1-.706.291H6.351a1 1 0 0 1-.625-.219L3.25 18.8a1 1 0 0 1 .631-1.781l4.165.027"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/package-2.js
var Package2 = [
  ["path", { d: "M12 3v6" }],
  [
    "path",
    {
      d: "M16.76 3a2 2 0 0 1 1.8 1.1l2.23 4.479a2 2 0 0 1 .21.891V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.472a2 2 0 0 1 .211-.894L5.45 4.1A2 2 0 0 1 7.24 3z"
    }
  ],
  ["path", { d: "M3.054 9.013h17.893" }]
];

// node_modules/lucide/dist/esm/icons/package-check.js
var PackageCheck = [
  ["path", { d: "m16 16 2 2 4-4" }],
  [
    "path",
    {
      d: "M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"
    }
  ],
  ["path", { d: "m7.5 4.27 9 5.15" }],
  ["polyline", { points: "3.29 7 12 12 20.71 7" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/package-minus.js
var PackageMinus = [
  ["path", { d: "M16 16h6" }],
  [
    "path",
    {
      d: "M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"
    }
  ],
  ["path", { d: "m7.5 4.27 9 5.15" }],
  ["polyline", { points: "3.29 7 12 12 20.71 7" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/package-open.js
var PackageOpen = [
  ["path", { d: "M12 22v-9" }],
  [
    "path",
    {
      d: "M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"
    }
  ],
  [
    "path",
    {
      d: "M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"
    }
  ],
  [
    "path",
    {
      d: "M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/package-plus.js
var PackagePlus = [
  ["path", { d: "M16 16h6" }],
  ["path", { d: "M19 13v6" }],
  [
    "path",
    {
      d: "M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"
    }
  ],
  ["path", { d: "m7.5 4.27 9 5.15" }],
  ["polyline", { points: "3.29 7 12 12 20.71 7" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/package-search.js
var PackageSearch = [
  [
    "path",
    {
      d: "M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"
    }
  ],
  ["path", { d: "m7.5 4.27 9 5.15" }],
  ["polyline", { points: "3.29 7 12 12 20.71 7" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "12" }],
  ["circle", { cx: "18.5", cy: "15.5", r: "2.5" }],
  ["path", { d: "M20.27 17.27 22 19" }]
];

// node_modules/lucide/dist/esm/icons/package-x.js
var PackageX = [
  [
    "path",
    {
      d: "M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"
    }
  ],
  ["path", { d: "m7.5 4.27 9 5.15" }],
  ["polyline", { points: "3.29 7 12 12 20.71 7" }],
  ["line", { x1: "12", x2: "12", y1: "22", y2: "12" }],
  ["path", { d: "m17 13 5 5m-5 0 5-5" }]
];

// node_modules/lucide/dist/esm/icons/package.js
var Package = [
  [
    "path",
    {
      d: "M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"
    }
  ],
  ["path", { d: "M12 22V12" }],
  ["polyline", { points: "3.29 7 12 12 20.71 7" }],
  ["path", { d: "m7.5 4.27 9 5.15" }]
];

// node_modules/lucide/dist/esm/icons/paint-bucket.js
var PaintBucket = [
  ["path", { d: "m19 11-8-8-8.6 8.6a2 2 0 0 0 0 2.8l5.2 5.2c.8.8 2 .8 2.8 0L19 11Z" }],
  ["path", { d: "m5 2 5 5" }],
  ["path", { d: "M2 13h15" }],
  ["path", { d: "M22 20a2 2 0 1 1-4 0c0-1.6 1.7-2.4 2-4 .3 1.6 2 2.4 2 4Z" }]
];

// node_modules/lucide/dist/esm/icons/paint-roller.js
var PaintRoller = [
  ["rect", { width: "16", height: "6", x: "2", y: "2", rx: "2" }],
  ["path", { d: "M10 16v-2a2 2 0 0 1 2-2h8a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" }],
  ["rect", { width: "4", height: "6", x: "8", y: "16", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/paintbrush-vertical.js
var PaintbrushVertical = [
  ["path", { d: "M10 2v2" }],
  ["path", { d: "M14 2v4" }],
  ["path", { d: "M17 2a1 1 0 0 1 1 1v9H6V3a1 1 0 0 1 1-1z" }],
  [
    "path",
    {
      d: "M6 12a1 1 0 0 0-1 1v1a2 2 0 0 0 2 2h2a1 1 0 0 1 1 1v2.9a2 2 0 1 0 4 0V17a1 1 0 0 1 1-1h2a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/paintbrush.js
var Paintbrush = [
  ["path", { d: "m14.622 17.897-10.68-2.913" }],
  [
    "path",
    {
      d: "M18.376 2.622a1 1 0 1 1 3.002 3.002L17.36 9.643a.5.5 0 0 0 0 .707l.944.944a2.41 2.41 0 0 1 0 3.408l-.944.944a.5.5 0 0 1-.707 0L8.354 7.348a.5.5 0 0 1 0-.707l.944-.944a2.41 2.41 0 0 1 3.408 0l.944.944a.5.5 0 0 0 .707 0z"
    }
  ],
  [
    "path",
    {
      d: "M9 8c-1.804 2.71-3.97 3.46-6.583 3.948a.507.507 0 0 0-.302.819l7.32 8.883a1 1 0 0 0 1.185.204C12.735 20.405 16 16.792 16 15"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/palette.js
var Palette = [
  [
    "path",
    {
      d: "M12 22a1 1 0 0 1 0-20 10 9 0 0 1 10 9 5 5 0 0 1-5 5h-2.25a1.75 1.75 0 0 0-1.4 2.8l.3.4a1.75 1.75 0 0 1-1.4 2.8z"
    }
  ],
  ["circle", { cx: "13.5", cy: "6.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "17.5", cy: "10.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "6.5", cy: "12.5", r: ".5", fill: "currentColor" }],
  ["circle", { cx: "8.5", cy: "7.5", r: ".5", fill: "currentColor" }]
];

// node_modules/lucide/dist/esm/icons/panel-bottom-close.js
var PanelBottomClose = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 15h18" }],
  ["path", { d: "m15 8-3 3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/panel-bottom-dashed.js
var PanelBottomDashed = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M14 15h1" }],
  ["path", { d: "M19 15h2" }],
  ["path", { d: "M3 15h2" }],
  ["path", { d: "M9 15h1" }]
];

// node_modules/lucide/dist/esm/icons/panel-bottom-open.js
var PanelBottomOpen = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 15h18" }],
  ["path", { d: "m9 10 3-3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/panel-bottom.js
var PanelBottom = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 15h18" }]
];

// node_modules/lucide/dist/esm/icons/panel-left-close.js
var PanelLeftClose = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 3v18" }],
  ["path", { d: "m16 15-3-3 3-3" }]
];

// node_modules/lucide/dist/esm/icons/panel-left-dashed.js
var PanelLeftDashed = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 14v1" }],
  ["path", { d: "M9 19v2" }],
  ["path", { d: "M9 3v2" }],
  ["path", { d: "M9 9v1" }]
];

// node_modules/lucide/dist/esm/icons/panel-left-open.js
var PanelLeftOpen = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 3v18" }],
  ["path", { d: "m14 9 3 3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/panel-left.js
var PanelLeft = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 3v18" }]
];

// node_modules/lucide/dist/esm/icons/panel-right-close.js
var PanelRightClose = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M15 3v18" }],
  ["path", { d: "m8 9 3 3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/panel-right-dashed.js
var PanelRightDashed = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M15 14v1" }],
  ["path", { d: "M15 19v2" }],
  ["path", { d: "M15 3v2" }],
  ["path", { d: "M15 9v1" }]
];

// node_modules/lucide/dist/esm/icons/panel-right.js
var PanelRight = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M15 3v18" }]
];

// node_modules/lucide/dist/esm/icons/panel-right-open.js
var PanelRightOpen = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M15 3v18" }],
  ["path", { d: "m10 15-3-3 3-3" }]
];

// node_modules/lucide/dist/esm/icons/panel-top-close.js
var PanelTopClose = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9h18" }],
  ["path", { d: "m9 16 3-3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/panel-top-dashed.js
var PanelTopDashed = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M14 9h1" }],
  ["path", { d: "M19 9h2" }],
  ["path", { d: "M3 9h2" }],
  ["path", { d: "M9 9h1" }]
];

// node_modules/lucide/dist/esm/icons/panel-top-open.js
var PanelTopOpen = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9h18" }],
  ["path", { d: "m15 14-3 3-3-3" }]
];

// node_modules/lucide/dist/esm/icons/panel-top.js
var PanelTop = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9h18" }]
];

// node_modules/lucide/dist/esm/icons/panels-left-bottom.js
var PanelsLeftBottom = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 3v18" }],
  ["path", { d: "M9 15h12" }]
];

// node_modules/lucide/dist/esm/icons/panels-right-bottom.js
var PanelsRightBottom = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 15h12" }],
  ["path", { d: "M15 3v18" }]
];

// node_modules/lucide/dist/esm/icons/panels-top-left.js
var PanelsTopLeft = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9h18" }],
  ["path", { d: "M9 21V9" }]
];

// node_modules/lucide/dist/esm/icons/paperclip.js
var Paperclip = [
  ["path", { d: "M13.234 20.252 21 12.3" }],
  [
    "path",
    {
      d: "m16 6-8.414 8.586a2 2 0 0 0 0 2.828 2 2 0 0 0 2.828 0l8.414-8.586a4 4 0 0 0 0-5.656 4 4 0 0 0-5.656 0l-8.415 8.585a6 6 0 1 0 8.486 8.486"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/parentheses.js
var Parentheses = [
  ["path", { d: "M8 21s-4-3-4-9 4-9 4-9" }],
  ["path", { d: "M16 3s4 3 4 9-4 9-4 9" }]
];

// node_modules/lucide/dist/esm/icons/parking-meter.js
var ParkingMeter = [
  ["path", { d: "M11 15h2" }],
  ["path", { d: "M12 12v3" }],
  ["path", { d: "M12 19v3" }],
  [
    "path",
    {
      d: "M15.282 19a1 1 0 0 0 .948-.68l2.37-6.988a7 7 0 1 0-13.2 0l2.37 6.988a1 1 0 0 0 .948.68z"
    }
  ],
  ["path", { d: "M9 9a3 3 0 1 1 6 0" }]
];

// node_modules/lucide/dist/esm/icons/party-popper.js
var PartyPopper = [
  ["path", { d: "M5.8 11.3 2 22l10.7-3.79" }],
  ["path", { d: "M4 3h.01" }],
  ["path", { d: "M22 8h.01" }],
  ["path", { d: "M15 2h.01" }],
  ["path", { d: "M22 20h.01" }],
  [
    "path",
    {
      d: "m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"
    }
  ],
  ["path", { d: "m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11c-.11.7-.72 1.22-1.43 1.22H17" }],
  ["path", { d: "m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7" }],
  [
    "path",
    {
      d: "M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/pause.js
var Pause = [
  ["rect", { x: "14", y: "4", width: "4", height: "16", rx: "1" }],
  ["rect", { x: "6", y: "4", width: "4", height: "16", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/paw-print.js
var PawPrint = [
  ["circle", { cx: "11", cy: "4", r: "2" }],
  ["circle", { cx: "18", cy: "8", r: "2" }],
  ["circle", { cx: "20", cy: "16", r: "2" }],
  [
    "path",
    {
      d: "M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/pc-case.js
var PcCase = [
  ["rect", { width: "14", height: "20", x: "5", y: "2", rx: "2" }],
  ["path", { d: "M15 14h.01" }],
  ["path", { d: "M9 6h6" }],
  ["path", { d: "M9 10h6" }]
];

// node_modules/lucide/dist/esm/icons/pen-line.js
var PenLine = [
  ["path", { d: "M12 20h9" }],
  [
    "path",
    {
      d: "M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/pen-off.js
var PenOff = [
  [
    "path",
    {
      d: "m10 10-6.157 6.162a2 2 0 0 0-.5.833l-1.322 4.36a.5.5 0 0 0 .622.624l4.358-1.323a2 2 0 0 0 .83-.5L14 13.982"
    }
  ],
  ["path", { d: "m12.829 7.172 4.359-4.346a1 1 0 1 1 3.986 3.986l-4.353 4.353" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/pen-tool.js
var PenTool = [
  [
    "path",
    {
      d: "M15.707 21.293a1 1 0 0 1-1.414 0l-1.586-1.586a1 1 0 0 1 0-1.414l5.586-5.586a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 1 0 1.414z"
    }
  ],
  [
    "path",
    {
      d: "m18 13-1.375-6.874a1 1 0 0 0-.746-.776L3.235 2.028a1 1 0 0 0-1.207 1.207L5.35 15.879a1 1 0 0 0 .776.746L13 18"
    }
  ],
  ["path", { d: "m2.3 2.3 7.286 7.286" }],
  ["circle", { cx: "11", cy: "11", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/pen.js
var Pen = [
  [
    "path",
    {
      d: "M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/pencil-line.js
var PencilLine = [
  ["path", { d: "M12 20h9" }],
  [
    "path",
    {
      d: "M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"
    }
  ],
  ["path", { d: "m15 5 3 3" }]
];

// node_modules/lucide/dist/esm/icons/pencil-off.js
var PencilOff = [
  [
    "path",
    {
      d: "m10 10-6.157 6.162a2 2 0 0 0-.5.833l-1.322 4.36a.5.5 0 0 0 .622.624l4.358-1.323a2 2 0 0 0 .83-.5L14 13.982"
    }
  ],
  ["path", { d: "m12.829 7.172 4.359-4.346a1 1 0 1 1 3.986 3.986l-4.353 4.353" }],
  ["path", { d: "m15 5 4 4" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/pencil-ruler.js
var PencilRuler = [
  ["path", { d: "M13 7 8.7 2.7a2.41 2.41 0 0 0-3.4 0L2.7 5.3a2.41 2.41 0 0 0 0 3.4L7 13" }],
  ["path", { d: "m8 6 2-2" }],
  ["path", { d: "m18 16 2-2" }],
  ["path", { d: "m17 11 4.3 4.3c.94.94.94 2.46 0 3.4l-2.6 2.6c-.94.94-2.46.94-3.4 0L11 17" }],
  [
    "path",
    {
      d: "M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
    }
  ],
  ["path", { d: "m15 5 4 4" }]
];

// node_modules/lucide/dist/esm/icons/pencil.js
var Pencil = [
  [
    "path",
    {
      d: "M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"
    }
  ],
  ["path", { d: "m15 5 4 4" }]
];

// node_modules/lucide/dist/esm/icons/pentagon.js
var Pentagon = [
  [
    "path",
    {
      d: "M10.83 2.38a2 2 0 0 1 2.34 0l8 5.74a2 2 0 0 1 .73 2.25l-3.04 9.26a2 2 0 0 1-1.9 1.37H7.04a2 2 0 0 1-1.9-1.37L2.1 10.37a2 2 0 0 1 .73-2.25z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/percent.js
var Percent = [
  ["line", { x1: "19", x2: "5", y1: "5", y2: "19" }],
  ["circle", { cx: "6.5", cy: "6.5", r: "2.5" }],
  ["circle", { cx: "17.5", cy: "17.5", r: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/person-standing.js
var PersonStanding = [
  ["circle", { cx: "12", cy: "5", r: "1" }],
  ["path", { d: "m9 20 3-6 3 6" }],
  ["path", { d: "m6 8 6 2 6-2" }],
  ["path", { d: "M12 10v4" }]
];

// node_modules/lucide/dist/esm/icons/philippine-peso.js
var PhilippinePeso = [
  ["path", { d: "M20 11H4" }],
  ["path", { d: "M20 7H4" }],
  ["path", { d: "M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7" }]
];

// node_modules/lucide/dist/esm/icons/phone-call.js
var PhoneCall = [
  [
    "path",
    {
      d: "M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
    }
  ],
  ["path", { d: "M14.05 2a9 9 0 0 1 8 7.94" }],
  ["path", { d: "M14.05 6A5 5 0 0 1 18 10" }]
];

// node_modules/lucide/dist/esm/icons/phone-forwarded.js
var PhoneForwarded = [
  ["polyline", { points: "18 2 22 6 18 10" }],
  ["line", { x1: "14", x2: "22", y1: "6", y2: "6" }],
  [
    "path",
    {
      d: "M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/phone-incoming.js
var PhoneIncoming = [
  ["polyline", { points: "16 2 16 8 22 8" }],
  ["line", { x1: "22", x2: "16", y1: "2", y2: "8" }],
  [
    "path",
    {
      d: "M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/phone-missed.js
var PhoneMissed = [
  ["line", { x1: "22", x2: "16", y1: "2", y2: "8" }],
  ["line", { x1: "16", x2: "22", y1: "2", y2: "8" }],
  [
    "path",
    {
      d: "M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/phone-off.js
var PhoneOff = [
  [
    "path",
    {
      d: "M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"
    }
  ],
  ["line", { x1: "22", x2: "2", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/phone-outgoing.js
var PhoneOutgoing = [
  ["polyline", { points: "22 8 22 2 16 2" }],
  ["line", { x1: "16", x2: "22", y1: "8", y2: "2" }],
  [
    "path",
    {
      d: "M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/phone.js
var Phone = [
  [
    "path",
    {
      d: "M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/piano.js
var Piano = [
  [
    "path",
    {
      d: "M18.5 8c-1.4 0-2.6-.8-3.2-2A6.87 6.87 0 0 0 2 9v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8.5C22 9.6 20.4 8 18.5 8"
    }
  ],
  ["path", { d: "M2 14h20" }],
  ["path", { d: "M6 14v4" }],
  ["path", { d: "M10 14v4" }],
  ["path", { d: "M14 14v4" }],
  ["path", { d: "M18 14v4" }]
];

// node_modules/lucide/dist/esm/icons/pi.js
var Pi = [
  ["line", { x1: "9", x2: "9", y1: "4", y2: "20" }],
  ["path", { d: "M4 7c0-1.7 1.3-3 3-3h13" }],
  ["path", { d: "M18 20c-1.7 0-3-1.3-3-3V4" }]
];

// node_modules/lucide/dist/esm/icons/pickaxe.js
var Pickaxe = [
  ["path", { d: "M14.531 12.469 6.619 20.38a1 1 0 1 1-3-3l7.912-7.912" }],
  [
    "path",
    { d: "M15.686 4.314A12.5 12.5 0 0 0 5.461 2.958 1 1 0 0 0 5.58 4.71a22 22 0 0 1 6.318 3.393" }
  ],
  [
    "path",
    {
      d: "M17.7 3.7a1 1 0 0 0-1.4 0l-4.6 4.6a1 1 0 0 0 0 1.4l2.6 2.6a1 1 0 0 0 1.4 0l4.6-4.6a1 1 0 0 0 0-1.4z"
    }
  ],
  [
    "path",
    {
      d: "M19.686 8.314a12.501 12.501 0 0 1 1.356 10.225 1 1 0 0 1-1.751-.119 22 22 0 0 0-3.393-6.319"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/picture-in-picture-2.js
var PictureInPicture2 = [
  ["path", { d: "M21 9V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h4" }],
  ["rect", { width: "10", height: "7", x: "12", y: "13", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/piggy-bank.js
var PiggyBank = [
  [
    "path",
    {
      d: "M11 17h3v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-3a3.16 3.16 0 0 0 2-2h1a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1h-1a5 5 0 0 0-2-4V3a4 4 0 0 0-3.2 1.6l-.3.4H11a6 6 0 0 0-6 6v1a5 5 0 0 0 2 4v3a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1z"
    }
  ],
  ["path", { d: "M16 10h.01" }],
  ["path", { d: "M2 8v1a2 2 0 0 0 2 2h1" }]
];

// node_modules/lucide/dist/esm/icons/picture-in-picture.js
var PictureInPicture = [
  ["path", { d: "M2 10h6V4" }],
  ["path", { d: "m2 4 6 6" }],
  ["path", { d: "M21 10V7a2 2 0 0 0-2-2h-7" }],
  ["path", { d: "M3 14v2a2 2 0 0 0 2 2h3" }],
  ["rect", { x: "12", y: "14", width: "10", height: "7", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/pilcrow-left.js
var PilcrowLeft = [
  ["path", { d: "M14 3v11" }],
  ["path", { d: "M14 9h-3a3 3 0 0 1 0-6h9" }],
  ["path", { d: "M18 3v11" }],
  ["path", { d: "M22 18H2l4-4" }],
  ["path", { d: "m6 22-4-4" }]
];

// node_modules/lucide/dist/esm/icons/pilcrow-right.js
var PilcrowRight = [
  ["path", { d: "M10 3v11" }],
  ["path", { d: "M10 9H7a1 1 0 0 1 0-6h8" }],
  ["path", { d: "M14 3v11" }],
  ["path", { d: "m18 14 4 4H2" }],
  ["path", { d: "m22 18-4 4" }]
];

// node_modules/lucide/dist/esm/icons/pilcrow.js
var Pilcrow = [
  ["path", { d: "M13 4v16" }],
  ["path", { d: "M17 4v16" }],
  ["path", { d: "M19 4H9.5a4.5 4.5 0 0 0 0 9H13" }]
];

// node_modules/lucide/dist/esm/icons/pill-bottle.js
var PillBottle = [
  ["path", { d: "M18 11h-4a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h4" }],
  ["path", { d: "M6 7v13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7" }],
  ["rect", { width: "16", height: "5", x: "4", y: "2", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/pill.js
var Pill = [
  ["path", { d: "m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z" }],
  ["path", { d: "m8.5 8.5 7 7" }]
];

// node_modules/lucide/dist/esm/icons/pin-off.js
var PinOff = [
  ["path", { d: "M12 17v5" }],
  ["path", { d: "M15 9.34V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H7.89" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M9 9v1.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h11" }]
];

// node_modules/lucide/dist/esm/icons/pin.js
var Pin = [
  ["path", { d: "M12 17v5" }],
  [
    "path",
    {
      d: "M9 10.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H8a2 2 0 0 0 0 4 1 1 0 0 1 1 1z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/plane-landing.js
var PlaneLanding = [
  ["path", { d: "M2 22h20" }],
  [
    "path",
    {
      d: "M3.77 10.77 2 9l2-4.5 1.1.55c.55.28.9.84.9 1.45s.35 1.17.9 1.45L8 8.5l3-6 1.05.53a2 2 0 0 1 1.09 1.52l.72 5.4a2 2 0 0 0 1.09 1.52l4.4 2.2c.42.22.78.55 1.01.96l.6 1.03c.49.88-.06 1.98-1.06 2.1l-1.18.15c-.47.06-.95-.02-1.37-.24L4.29 11.15a2 2 0 0 1-.52-.38Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/pipette.js
var Pipette = [
  [
    "path",
    {
      d: "m12 9-8.414 8.414A2 2 0 0 0 3 18.828v1.344a2 2 0 0 1-.586 1.414A2 2 0 0 1 3.828 21h1.344a2 2 0 0 0 1.414-.586L15 12"
    }
  ],
  ["path", { d: "m18 9 .4.4a1 1 0 1 1-3 3l-3.8-3.8a1 1 0 1 1 3-3l.4.4 3.4-3.4a1 1 0 1 1 3 3z" }],
  ["path", { d: "m2 22 .414-.414" }]
];

// node_modules/lucide/dist/esm/icons/pizza.js
var Pizza = [
  ["path", { d: "m12 14-1 1" }],
  ["path", { d: "m13.75 18.25-1.25 1.42" }],
  ["path", { d: "M17.775 5.654a15.68 15.68 0 0 0-12.121 12.12" }],
  ["path", { d: "M18.8 9.3a1 1 0 0 0 2.1 7.7" }],
  [
    "path",
    {
      d: "M21.964 20.732a1 1 0 0 1-1.232 1.232l-18-5a1 1 0 0 1-.695-1.232A19.68 19.68 0 0 1 15.732 2.037a1 1 0 0 1 1.232.695z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/plane-takeoff.js
var PlaneTakeoff = [
  ["path", { d: "M2 22h20" }],
  [
    "path",
    {
      d: "M6.36 17.4 4 17l-2-4 1.1-.55a2 2 0 0 1 1.8 0l.17.1a2 2 0 0 0 1.8 0L8 12 5 6l.9-.45a2 2 0 0 1 2.09.2l4.02 3a2 2 0 0 0 2.1.2l4.19-2.06a2.41 2.41 0 0 1 1.73-.17L21 7a1.4 1.4 0 0 1 .87 1.99l-.38.76c-.23.46-.6.84-1.07 1.08L7.58 17.2a2 2 0 0 1-1.22.18Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/plane.js
var Plane = [
  [
    "path",
    {
      d: "M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/play.js
var Play = [["polygon", { points: "6 3 20 12 6 21 6 3" }]];

// node_modules/lucide/dist/esm/icons/plug-2.js
var Plug2 = [
  ["path", { d: "M9 2v6" }],
  ["path", { d: "M15 2v6" }],
  ["path", { d: "M12 17v5" }],
  ["path", { d: "M5 8h14" }],
  ["path", { d: "M6 11V8h12v3a6 6 0 1 1-12 0Z" }]
];

// node_modules/lucide/dist/esm/icons/plug-zap.js
var PlugZap = [
  ["path", { d: "M6.3 20.3a2.4 2.4 0 0 0 3.4 0L12 18l-6-6-2.3 2.3a2.4 2.4 0 0 0 0 3.4Z" }],
  ["path", { d: "m2 22 3-3" }],
  ["path", { d: "M7.5 13.5 10 11" }],
  ["path", { d: "M10.5 16.5 13 14" }],
  ["path", { d: "m18 3-4 4h6l-4 4" }]
];

// node_modules/lucide/dist/esm/icons/plug.js
var Plug = [
  ["path", { d: "M12 22v-5" }],
  ["path", { d: "M9 8V2" }],
  ["path", { d: "M15 8V2" }],
  ["path", { d: "M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z" }]
];

// node_modules/lucide/dist/esm/icons/plus.js
var Plus = [
  ["path", { d: "M5 12h14" }],
  ["path", { d: "M12 5v14" }]
];

// node_modules/lucide/dist/esm/icons/pocket-knife.js
var PocketKnife = [
  ["path", { d: "M3 2v1c0 1 2 1 2 2S3 6 3 7s2 1 2 2-2 1-2 2 2 1 2 2" }],
  ["path", { d: "M18 6h.01" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "M20.83 8.83a4 4 0 0 0-5.66-5.66l-12 12a4 4 0 1 0 5.66 5.66Z" }],
  ["path", { d: "M18 11.66V22a4 4 0 0 0 4-4V6" }]
];

// node_modules/lucide/dist/esm/icons/pocket.js
var Pocket = [
  ["path", { d: "M4 3h16a2 2 0 0 1 2 2v6a10 10 0 0 1-10 10A10 10 0 0 1 2 11V5a2 2 0 0 1 2-2z" }],
  ["polyline", { points: "8 10 12 14 16 10" }]
];

// node_modules/lucide/dist/esm/icons/podcast.js
var Podcast = [
  ["path", { d: "M16.85 18.58a9 9 0 1 0-9.7 0" }],
  ["path", { d: "M8 14a5 5 0 1 1 8 0" }],
  ["circle", { cx: "12", cy: "11", r: "1" }],
  ["path", { d: "M13 17a1 1 0 1 0-2 0l.5 4.5a.5.5 0 1 0 1 0Z" }]
];

// node_modules/lucide/dist/esm/icons/pointer-off.js
var PointerOff = [
  ["path", { d: "M10 4.5V4a2 2 0 0 0-2.41-1.957" }],
  ["path", { d: "M13.9 8.4a2 2 0 0 0-1.26-1.295" }],
  ["path", { d: "M21.7 16.2A8 8 0 0 0 22 14v-3a2 2 0 1 0-4 0v-1a2 2 0 0 0-3.63-1.158" }],
  [
    "path",
    { d: "m7 15-1.8-1.8a2 2 0 0 0-2.79 2.86L6 19.7a7.74 7.74 0 0 0 6 2.3h2a8 8 0 0 0 5.657-2.343" }
  ],
  ["path", { d: "M6 6v8" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/pointer.js
var Pointer = [
  ["path", { d: "M22 14a8 8 0 0 1-8 8" }],
  ["path", { d: "M18 11v-1a2 2 0 0 0-2-2a2 2 0 0 0-2 2" }],
  ["path", { d: "M14 10V9a2 2 0 0 0-2-2a2 2 0 0 0-2 2v1" }],
  ["path", { d: "M10 9.5V4a2 2 0 0 0-2-2a2 2 0 0 0-2 2v10" }],
  [
    "path",
    {
      d: "M18 11a2 2 0 1 1 4 0v3a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/popcorn.js
var Popcorn = [
  ["path", { d: "M18 8a2 2 0 0 0 0-4 2 2 0 0 0-4 0 2 2 0 0 0-4 0 2 2 0 0 0-4 0 2 2 0 0 0 0 4" }],
  ["path", { d: "M10 22 9 8" }],
  ["path", { d: "m14 22 1-14" }],
  [
    "path",
    {
      d: "M20 8c.5 0 .9.4.8 1l-2.6 12c-.1.5-.7 1-1.2 1H7c-.6 0-1.1-.4-1.2-1L3.2 9c-.1-.6.3-1 .8-1Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/popsicle.js
var Popsicle = [
  [
    "path",
    { d: "M18.6 14.4c.8-.8.8-2 0-2.8l-8.1-8.1a4.95 4.95 0 1 0-7.1 7.1l8.1 8.1c.9.7 2.1.7 2.9-.1Z" }
  ],
  ["path", { d: "m22 22-5.5-5.5" }]
];

// node_modules/lucide/dist/esm/icons/power-off.js
var PowerOff = [
  ["path", { d: "M18.36 6.64A9 9 0 0 1 20.77 15" }],
  ["path", { d: "M6.16 6.16a9 9 0 1 0 12.68 12.68" }],
  ["path", { d: "M12 2v4" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/pound-sterling.js
var PoundSterling = [
  ["path", { d: "M18 7c0-5.333-8-5.333-8 0" }],
  ["path", { d: "M10 7v14" }],
  ["path", { d: "M6 21h12" }],
  ["path", { d: "M6 13h10" }]
];

// node_modules/lucide/dist/esm/icons/power.js
var Power = [
  ["path", { d: "M12 2v10" }],
  ["path", { d: "M18.4 6.6a9 9 0 1 1-12.77.04" }]
];

// node_modules/lucide/dist/esm/icons/presentation.js
var Presentation = [
  ["path", { d: "M2 3h20" }],
  ["path", { d: "M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3" }],
  ["path", { d: "m7 21 5-5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/printer-check.js
var PrinterCheck = [
  ["path", { d: "M13.5 22H7a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v.5" }],
  ["path", { d: "m16 19 2 2 4-4" }],
  ["path", { d: "M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" }]
];

// node_modules/lucide/dist/esm/icons/printer.js
var Printer = [
  ["path", { d: "M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" }],
  ["rect", { x: "6", y: "14", width: "12", height: "8", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/projector.js
var Projector = [
  ["path", { d: "M5 7 3 5" }],
  ["path", { d: "M9 6V3" }],
  ["path", { d: "m13 7 2-2" }],
  ["circle", { cx: "9", cy: "13", r: "3" }],
  [
    "path",
    { d: "M11.83 12H20a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h2.17" }
  ],
  ["path", { d: "M16 16h2" }]
];

// node_modules/lucide/dist/esm/icons/proportions.js
var Proportions = [
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["path", { d: "M12 9v11" }],
  ["path", { d: "M2 9h13a2 2 0 0 1 2 2v9" }]
];

// node_modules/lucide/dist/esm/icons/puzzle.js
var Puzzle = [
  [
    "path",
    {
      d: "M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/pyramid.js
var Pyramid = [
  [
    "path",
    {
      d: "M2.5 16.88a1 1 0 0 1-.32-1.43l9-13.02a1 1 0 0 1 1.64 0l9 13.01a1 1 0 0 1-.32 1.44l-8.51 4.86a2 2 0 0 1-1.98 0Z"
    }
  ],
  ["path", { d: "M12 2v20" }]
];

// node_modules/lucide/dist/esm/icons/qr-code.js
var QrCode = [
  ["rect", { width: "5", height: "5", x: "3", y: "3", rx: "1" }],
  ["rect", { width: "5", height: "5", x: "16", y: "3", rx: "1" }],
  ["rect", { width: "5", height: "5", x: "3", y: "16", rx: "1" }],
  ["path", { d: "M21 16h-3a2 2 0 0 0-2 2v3" }],
  ["path", { d: "M21 21v.01" }],
  ["path", { d: "M12 7v3a2 2 0 0 1-2 2H7" }],
  ["path", { d: "M3 12h.01" }],
  ["path", { d: "M12 3h.01" }],
  ["path", { d: "M12 16v.01" }],
  ["path", { d: "M16 12h1" }],
  ["path", { d: "M21 12v.01" }],
  ["path", { d: "M12 21v-1" }]
];

// node_modules/lucide/dist/esm/icons/quote.js
var Quote = [
  [
    "path",
    {
      d: "M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"
    }
  ],
  [
    "path",
    {
      d: "M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/rabbit.js
var Rabbit = [
  ["path", { d: "M13 16a3 3 0 0 1 2.24 5" }],
  ["path", { d: "M18 12h.01" }],
  [
    "path",
    {
      d: "M18 21h-8a4 4 0 0 1-4-4 7 7 0 0 1 7-7h.2L9.6 6.4a1 1 0 1 1 2.8-2.8L15.8 7h.2c3.3 0 6 2.7 6 6v1a2 2 0 0 1-2 2h-1a3 3 0 0 0-3 3"
    }
  ],
  ["path", { d: "M20 8.54V4a2 2 0 1 0-4 0v3" }],
  ["path", { d: "M7.612 12.524a3 3 0 1 0-1.6 4.3" }]
];

// node_modules/lucide/dist/esm/icons/radar.js
var Radar = [
  ["path", { d: "M19.07 4.93A10 10 0 0 0 6.99 3.34" }],
  ["path", { d: "M4 6h.01" }],
  ["path", { d: "M2.29 9.62A10 10 0 1 0 21.31 8.35" }],
  ["path", { d: "M16.24 7.76A6 6 0 1 0 8.23 16.67" }],
  ["path", { d: "M12 18h.01" }],
  ["path", { d: "M17.99 11.66A6 6 0 0 1 15.77 16.67" }],
  ["circle", { cx: "12", cy: "12", r: "2" }],
  ["path", { d: "m13.41 10.59 5.66-5.66" }]
];

// node_modules/lucide/dist/esm/icons/radiation.js
var Radiation = [
  ["path", { d: "M12 12h.01" }],
  [
    "path",
    {
      d: "M7.5 4.2c-.3-.5-.9-.7-1.3-.4C3.9 5.5 2.3 8.1 2 11c-.1.5.4 1 1 1h5c0-1.5.8-2.8 2-3.4-1.1-1.9-2-3.5-2.5-4.4z"
    }
  ],
  [
    "path",
    {
      d: "M21 12c.6 0 1-.4 1-1-.3-2.9-1.8-5.5-4.1-7.1-.4-.3-1.1-.2-1.3.3-.6.9-1.5 2.5-2.6 4.3 1.2.7 2 2 2 3.5h5z"
    }
  ],
  [
    "path",
    {
      d: "M7.5 19.8c-.3.5-.1 1.1.4 1.3 2.6 1.2 5.6 1.2 8.2 0 .5-.2.7-.8.4-1.3-.5-.9-1.4-2.5-2.5-4.3-1.2.7-2.8.7-4 0-1.1 1.8-2 3.4-2.5 4.3z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/radical.js
var Radical = [
  [
    "path",
    {
      d: "M3 12h3.28a1 1 0 0 1 .948.684l2.298 7.934a.5.5 0 0 0 .96-.044L13.82 4.771A1 1 0 0 1 14.792 4H21"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/radio-receiver.js
var RadioReceiver = [
  ["path", { d: "M5 16v2" }],
  ["path", { d: "M19 16v2" }],
  ["rect", { width: "20", height: "8", x: "2", y: "8", rx: "2" }],
  ["path", { d: "M18 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/radio-tower.js
var RadioTower = [
  ["path", { d: "M4.9 16.1C1 12.2 1 5.8 4.9 1.9" }],
  ["path", { d: "M7.8 4.7a6.14 6.14 0 0 0-.8 7.5" }],
  ["circle", { cx: "12", cy: "9", r: "2" }],
  ["path", { d: "M16.2 4.8c2 2 2.26 5.11.8 7.47" }],
  ["path", { d: "M19.1 1.9a9.96 9.96 0 0 1 0 14.1" }],
  ["path", { d: "M9.5 18h5" }],
  ["path", { d: "m8 22 4-11 4 11" }]
];

// node_modules/lucide/dist/esm/icons/radio.js
var Radio = [
  ["path", { d: "M4.9 19.1C1 15.2 1 8.8 4.9 4.9" }],
  ["path", { d: "M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5" }],
  ["circle", { cx: "12", cy: "12", r: "2" }],
  ["path", { d: "M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5" }],
  ["path", { d: "M19.1 4.9C23 8.8 23 15.1 19.1 19" }]
];

// node_modules/lucide/dist/esm/icons/radius.js
var Radius = [
  ["path", { d: "M20.34 17.52a10 10 0 1 0-2.82 2.82" }],
  ["circle", { cx: "19", cy: "19", r: "2" }],
  ["path", { d: "m13.41 13.41 4.18 4.18" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/rail-symbol.js
var RailSymbol = [
  ["path", { d: "M5 15h14" }],
  ["path", { d: "M5 9h14" }],
  ["path", { d: "m14 20-5-5 6-6-5-5" }]
];

// node_modules/lucide/dist/esm/icons/rainbow.js
var Rainbow = [
  ["path", { d: "M22 17a10 10 0 0 0-20 0" }],
  ["path", { d: "M6 17a6 6 0 0 1 12 0" }],
  ["path", { d: "M10 17a2 2 0 0 1 4 0" }]
];

// node_modules/lucide/dist/esm/icons/rat.js
var Rat = [
  ["path", { d: "M13 22H4a2 2 0 0 1 0-4h12" }],
  ["path", { d: "M13.236 18a3 3 0 0 0-2.2-5" }],
  ["path", { d: "M16 9h.01" }],
  [
    "path",
    {
      d: "M16.82 3.94a3 3 0 1 1 3.237 4.868l1.815 2.587a1.5 1.5 0 0 1-1.5 2.1l-2.872-.453a3 3 0 0 0-3.5 3"
    }
  ],
  ["path", { d: "M17 4.988a3 3 0 1 0-5.2 2.052A7 7 0 0 0 4 14.015 4 4 0 0 0 8 18" }]
];

// node_modules/lucide/dist/esm/icons/ratio.js
var Ratio = [
  ["rect", { width: "12", height: "20", x: "6", y: "2", rx: "2" }],
  ["rect", { width: "20", height: "12", x: "2", y: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/receipt-cent.js
var ReceiptCent = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M12 6.5v11" }],
  ["path", { d: "M15 9.4a4 4 0 1 0 0 5.2" }]
];

// node_modules/lucide/dist/esm/icons/receipt-indian-rupee.js
var ReceiptIndianRupee = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M8 7h8" }],
  ["path", { d: "M12 17.5 8 15h1a4 4 0 0 0 0-8" }],
  ["path", { d: "M8 11h8" }]
];

// node_modules/lucide/dist/esm/icons/receipt-euro.js
var ReceiptEuro = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M8 12h5" }],
  ["path", { d: "M16 9.5a4 4 0 1 0 0 5.2" }]
];

// node_modules/lucide/dist/esm/icons/receipt-japanese-yen.js
var ReceiptJapaneseYen = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "m12 10 3-3" }],
  ["path", { d: "m9 7 3 3v7.5" }],
  ["path", { d: "M9 11h6" }],
  ["path", { d: "M9 15h6" }]
];

// node_modules/lucide/dist/esm/icons/receipt-pound-sterling.js
var ReceiptPoundSterling = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M8 13h5" }],
  ["path", { d: "M10 17V9.5a2.5 2.5 0 0 1 5 0" }],
  ["path", { d: "M8 17h7" }]
];

// node_modules/lucide/dist/esm/icons/receipt-russian-ruble.js
var ReceiptRussianRuble = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M8 15h5" }],
  ["path", { d: "M8 11h5a2 2 0 1 0 0-4h-3v10" }]
];

// node_modules/lucide/dist/esm/icons/receipt-swiss-franc.js
var ReceiptSwissFranc = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M10 17V7h5" }],
  ["path", { d: "M10 11h4" }],
  ["path", { d: "M8 15h5" }]
];

// node_modules/lucide/dist/esm/icons/receipt.js
var Receipt = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" }],
  ["path", { d: "M12 17.5v-11" }]
];

// node_modules/lucide/dist/esm/icons/receipt-text.js
var ReceiptText = [
  ["path", { d: "M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" }],
  ["path", { d: "M14 8H8" }],
  ["path", { d: "M16 12H8" }],
  ["path", { d: "M13 16H8" }]
];

// node_modules/lucide/dist/esm/icons/rectangle-ellipsis.js
var RectangleEllipsis = [
  ["rect", { width: "20", height: "12", x: "2", y: "6", rx: "2" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M17 12h.01" }],
  ["path", { d: "M7 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/rectangle-horizontal.js
var RectangleHorizontal = [
  ["rect", { width: "20", height: "12", x: "2", y: "6", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/rectangle-goggles.js
var RectangleGoggles = [
  [
    "path",
    {
      d: "M20 6a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-4a2 2 0 0 1-1.6-.8l-1.6-2.13a1 1 0 0 0-1.6 0L9.6 17.2A2 2 0 0 1 8 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/rectangle-vertical.js
var RectangleVertical = [
  ["rect", { width: "12", height: "20", x: "6", y: "2", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/recycle.js
var Recycle = [
  ["path", { d: "M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5" }],
  ["path", { d: "M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12" }],
  ["path", { d: "m14 16-3 3 3 3" }],
  ["path", { d: "M8.293 13.596 7.196 9.5 3.1 10.598" }],
  [
    "path",
    {
      d: "m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843"
    }
  ],
  ["path", { d: "m13.378 9.633 4.096 1.098 1.097-4.096" }]
];

// node_modules/lucide/dist/esm/icons/redo-2.js
var Redo2 = [
  ["path", { d: "m15 14 5-5-5-5" }],
  ["path", { d: "M20 9H9.5A5.5 5.5 0 0 0 4 14.5A5.5 5.5 0 0 0 9.5 20H13" }]
];

// node_modules/lucide/dist/esm/icons/redo-dot.js
var RedoDot = [
  ["circle", { cx: "12", cy: "17", r: "1" }],
  ["path", { d: "M21 7v6h-6" }],
  ["path", { d: "M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7" }]
];

// node_modules/lucide/dist/esm/icons/redo.js
var Redo = [
  ["path", { d: "M21 7v6h-6" }],
  ["path", { d: "M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3l3 2.7" }]
];

// node_modules/lucide/dist/esm/icons/refresh-ccw-dot.js
var RefreshCcwDot = [
  ["path", { d: "M3 2v6h6" }],
  ["path", { d: "M21 12A9 9 0 0 0 6 5.3L3 8" }],
  ["path", { d: "M21 22v-6h-6" }],
  ["path", { d: "M3 12a9 9 0 0 0 15 6.7l3-2.7" }],
  ["circle", { cx: "12", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/refresh-cw-off.js
var RefreshCwOff = [
  ["path", { d: "M21 8L18.74 5.74A9.75 9.75 0 0 0 12 3C11 3 10.03 3.16 9.13 3.47" }],
  ["path", { d: "M8 16H3v5" }],
  ["path", { d: "M3 12C3 9.51 4 7.26 5.64 5.64" }],
  ["path", { d: "m3 16 2.26 2.26A9.75 9.75 0 0 0 12 21c2.49 0 4.74-1 6.36-2.64" }],
  ["path", { d: "M21 12c0 1-.16 1.97-.47 2.87" }],
  ["path", { d: "M21 3v5h-5" }],
  ["path", { d: "M22 22 2 2" }]
];

// node_modules/lucide/dist/esm/icons/refresh-ccw.js
var RefreshCcw = [
  ["path", { d: "M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" }],
  ["path", { d: "M3 3v5h5" }],
  ["path", { d: "M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16" }],
  ["path", { d: "M16 16h5v5" }]
];

// node_modules/lucide/dist/esm/icons/refresh-cw.js
var RefreshCw = [
  ["path", { d: "M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" }],
  ["path", { d: "M21 3v5h-5" }],
  ["path", { d: "M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" }],
  ["path", { d: "M8 16H3v5" }]
];

// node_modules/lucide/dist/esm/icons/refrigerator.js
var Refrigerator = [
  ["path", { d: "M5 6a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6Z" }],
  ["path", { d: "M5 10h14" }],
  ["path", { d: "M15 7v6" }]
];

// node_modules/lucide/dist/esm/icons/regex.js
var Regex = [
  ["path", { d: "M17 3v10" }],
  ["path", { d: "m12.67 5.5 8.66 5" }],
  ["path", { d: "m12.67 10.5 8.66-5" }],
  ["path", { d: "M9 17a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2z" }]
];

// node_modules/lucide/dist/esm/icons/remove-formatting.js
var RemoveFormatting = [
  ["path", { d: "M4 7V4h16v3" }],
  ["path", { d: "M5 20h6" }],
  ["path", { d: "M13 4 8 20" }],
  ["path", { d: "m15 15 5 5" }],
  ["path", { d: "m20 15-5 5" }]
];

// node_modules/lucide/dist/esm/icons/repeat-1.js
var Repeat1 = [
  ["path", { d: "m17 2 4 4-4 4" }],
  ["path", { d: "M3 11v-1a4 4 0 0 1 4-4h14" }],
  ["path", { d: "m7 22-4-4 4-4" }],
  ["path", { d: "M21 13v1a4 4 0 0 1-4 4H3" }],
  ["path", { d: "M11 10h1v4" }]
];

// node_modules/lucide/dist/esm/icons/repeat-2.js
var Repeat2 = [
  ["path", { d: "m2 9 3-3 3 3" }],
  ["path", { d: "M13 18H7a2 2 0 0 1-2-2V6" }],
  ["path", { d: "m22 15-3 3-3-3" }],
  ["path", { d: "M11 6h6a2 2 0 0 1 2 2v10" }]
];

// node_modules/lucide/dist/esm/icons/repeat.js
var Repeat = [
  ["path", { d: "m17 2 4 4-4 4" }],
  ["path", { d: "M3 11v-1a4 4 0 0 1 4-4h14" }],
  ["path", { d: "m7 22-4-4 4-4" }],
  ["path", { d: "M21 13v1a4 4 0 0 1-4 4H3" }]
];

// node_modules/lucide/dist/esm/icons/replace-all.js
var ReplaceAll = [
  ["path", { d: "M14 14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2" }],
  ["path", { d: "M14 4a2 2 0 0 1 2-2" }],
  ["path", { d: "M16 10a2 2 0 0 1-2-2" }],
  ["path", { d: "M20 14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2" }],
  ["path", { d: "M20 2a2 2 0 0 1 2 2" }],
  ["path", { d: "M22 8a2 2 0 0 1-2 2" }],
  ["path", { d: "m3 7 3 3 3-3" }],
  ["path", { d: "M6 10V5a 3 3 0 0 1 3-3h1" }],
  ["rect", { x: "2", y: "14", width: "8", height: "8", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/replace.js
var Replace = [
  ["path", { d: "M14 4a2 2 0 0 1 2-2" }],
  ["path", { d: "M16 10a2 2 0 0 1-2-2" }],
  ["path", { d: "M20 2a2 2 0 0 1 2 2" }],
  ["path", { d: "M22 8a2 2 0 0 1-2 2" }],
  ["path", { d: "m3 7 3 3 3-3" }],
  ["path", { d: "M6 10V5a3 3 0 0 1 3-3h1" }],
  ["rect", { x: "2", y: "14", width: "8", height: "8", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/reply-all.js
var ReplyAll = [
  ["polyline", { points: "7 17 2 12 7 7" }],
  ["polyline", { points: "12 17 7 12 12 7" }],
  ["path", { d: "M22 18v-2a4 4 0 0 0-4-4H7" }]
];

// node_modules/lucide/dist/esm/icons/reply.js
var Reply = [
  ["polyline", { points: "9 17 4 12 9 7" }],
  ["path", { d: "M20 18v-2a4 4 0 0 0-4-4H4" }]
];

// node_modules/lucide/dist/esm/icons/rewind.js
var Rewind = [
  ["polygon", { points: "11 19 2 12 11 5 11 19" }],
  ["polygon", { points: "22 19 13 12 22 5 22 19" }]
];

// node_modules/lucide/dist/esm/icons/ribbon.js
var Ribbon = [
  ["path", { d: "M12 11.22C11 9.997 10 9 10 8a2 2 0 0 1 4 0c0 1-.998 2.002-2.01 3.22" }],
  ["path", { d: "m12 18 2.57-3.5" }],
  ["path", { d: "M6.243 9.016a7 7 0 0 1 11.507-.009" }],
  ["path", { d: "M9.35 14.53 12 11.22" }],
  [
    "path",
    {
      d: "M9.35 14.53C7.728 12.246 6 10.221 6 7a6 5 0 0 1 12 0c-.005 3.22-1.778 5.235-3.43 7.5l3.557 4.527a1 1 0 0 1-.203 1.43l-1.894 1.36a1 1 0 0 1-1.384-.215L12 18l-2.679 3.593a1 1 0 0 1-1.39.213l-1.865-1.353a1 1 0 0 1-.203-1.422z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/rocket.js
var Rocket = [
  [
    "path",
    {
      d: "M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"
    }
  ],
  [
    "path",
    {
      d: "m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"
    }
  ],
  ["path", { d: "M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0" }],
  ["path", { d: "M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5" }]
];

// node_modules/lucide/dist/esm/icons/rocking-chair.js
var RockingChair = [
  ["polyline", { points: "3.5 2 6.5 12.5 18 12.5" }],
  ["line", { x1: "9.5", x2: "5.5", y1: "12.5", y2: "20" }],
  ["line", { x1: "15", x2: "18.5", y1: "12.5", y2: "20" }],
  ["path", { d: "M2.75 18a13 13 0 0 0 18.5 0" }]
];

// node_modules/lucide/dist/esm/icons/roller-coaster.js
var RollerCoaster = [
  ["path", { d: "M6 19V5" }],
  ["path", { d: "M10 19V6.8" }],
  ["path", { d: "M14 19v-7.8" }],
  ["path", { d: "M18 5v4" }],
  ["path", { d: "M18 19v-6" }],
  ["path", { d: "M22 19V9" }],
  ["path", { d: "M2 19V9a4 4 0 0 1 4-4c2 0 4 1.33 6 4s4 4 6 4a4 4 0 1 0-3-6.65" }]
];

// node_modules/lucide/dist/esm/icons/rotate-3d.js
var Rotate3d = [
  [
    "path",
    {
      d: "M16.466 7.5C15.643 4.237 13.952 2 12 2 9.239 2 7 6.477 7 12s2.239 10 5 10c.342 0 .677-.069 1-.2"
    }
  ],
  ["path", { d: "m15.194 13.707 3.814 1.86-1.86 3.814" }],
  [
    "path",
    {
      d: "M19 15.57c-1.804.885-4.274 1.43-7 1.43-5.523 0-10-2.239-10-5s4.477-5 10-5c4.838 0 8.873 1.718 9.8 4"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/rotate-ccw-key.js
var RotateCcwKey = [
  ["path", { d: "m14.5 9.5 1 1" }],
  ["path", { d: "m15.5 8.5-4 4" }],
  ["path", { d: "M3 12a9 9 0 1 0 9-9 9.74 9.74 0 0 0-6.74 2.74L3 8" }],
  ["path", { d: "M3 3v5h5" }],
  ["circle", { cx: "10", cy: "14", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/rotate-ccw-square.js
var RotateCcwSquare = [
  ["path", { d: "M20 9V7a2 2 0 0 0-2-2h-6" }],
  ["path", { d: "m15 2-3 3 3 3" }],
  ["path", { d: "M20 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2" }]
];

// node_modules/lucide/dist/esm/icons/rotate-ccw.js
var RotateCcw = [
  ["path", { d: "M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" }],
  ["path", { d: "M3 3v5h5" }]
];

// node_modules/lucide/dist/esm/icons/rotate-cw-square.js
var RotateCwSquare = [
  ["path", { d: "M12 5H6a2 2 0 0 0-2 2v3" }],
  ["path", { d: "m9 8 3-3-3-3" }],
  ["path", { d: "M4 14v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" }]
];

// node_modules/lucide/dist/esm/icons/rotate-cw.js
var RotateCw = [
  ["path", { d: "M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" }],
  ["path", { d: "M21 3v5h-5" }]
];

// node_modules/lucide/dist/esm/icons/route-off.js
var RouteOff = [
  ["circle", { cx: "6", cy: "19", r: "3" }],
  ["path", { d: "M9 19h8.5c.4 0 .9-.1 1.3-.2" }],
  ["path", { d: "M5.2 5.2A3.5 3.53 0 0 0 6.5 12H12" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M21 15.3a3.5 3.5 0 0 0-3.3-3.3" }],
  ["path", { d: "M15 5h-4.3" }],
  ["circle", { cx: "18", cy: "5", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/route.js
var Route = [
  ["circle", { cx: "6", cy: "19", r: "3" }],
  ["path", { d: "M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15" }],
  ["circle", { cx: "18", cy: "5", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/router.js
var Router = [
  ["rect", { width: "20", height: "8", x: "2", y: "14", rx: "2" }],
  ["path", { d: "M6.01 18H6" }],
  ["path", { d: "M10.01 18H10" }],
  ["path", { d: "M15 10v4" }],
  ["path", { d: "M17.84 7.17a4 4 0 0 0-5.66 0" }],
  ["path", { d: "M20.66 4.34a8 8 0 0 0-11.31 0" }]
];

// node_modules/lucide/dist/esm/icons/rows-2.js
var Rows2 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 12h18" }]
];

// node_modules/lucide/dist/esm/icons/rows-4.js
var Rows4 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M21 7.5H3" }],
  ["path", { d: "M21 12H3" }],
  ["path", { d: "M21 16.5H3" }]
];

// node_modules/lucide/dist/esm/icons/rows-3.js
var Rows3 = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M21 9H3" }],
  ["path", { d: "M21 15H3" }]
];

// node_modules/lucide/dist/esm/icons/rss.js
var Rss = [
  ["path", { d: "M4 11a9 9 0 0 1 9 9" }],
  ["path", { d: "M4 4a16 16 0 0 1 16 16" }],
  ["circle", { cx: "5", cy: "19", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/ruler-dimension-line.js
var RulerDimensionLine = [
  ["path", { d: "M12 15v-3.014" }],
  ["path", { d: "M16 15v-3.014" }],
  ["path", { d: "M20 6H4" }],
  ["path", { d: "M20 8V4" }],
  ["path", { d: "M4 8V4" }],
  ["path", { d: "M8 15v-3.014" }],
  ["rect", { x: "3", y: "12", width: "18", height: "7", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/ruler.js
var Ruler = [
  [
    "path",
    {
      d: "M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"
    }
  ],
  ["path", { d: "m14.5 12.5 2-2" }],
  ["path", { d: "m11.5 9.5 2-2" }],
  ["path", { d: "m8.5 6.5 2-2" }],
  ["path", { d: "m17.5 15.5 2-2" }]
];

// node_modules/lucide/dist/esm/icons/russian-ruble.js
var RussianRuble = [
  ["path", { d: "M6 11h8a4 4 0 0 0 0-8H9v18" }],
  ["path", { d: "M6 15h8" }]
];

// node_modules/lucide/dist/esm/icons/sailboat.js
var Sailboat = [
  ["path", { d: "M22 18H2a4 4 0 0 0 4 4h12a4 4 0 0 0 4-4Z" }],
  ["path", { d: "M21 14 10 2 3 14h18Z" }],
  ["path", { d: "M10 2v16" }]
];

// node_modules/lucide/dist/esm/icons/salad.js
var Salad = [
  ["path", { d: "M7 21h10" }],
  ["path", { d: "M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9Z" }],
  [
    "path",
    {
      d: "M11.38 12a2.4 2.4 0 0 1-.4-4.77 2.4 2.4 0 0 1 3.2-2.77 2.4 2.4 0 0 1 3.47-.63 2.4 2.4 0 0 1 3.37 3.37 2.4 2.4 0 0 1-1.1 3.7 2.51 2.51 0 0 1 .03 1.1"
    }
  ],
  ["path", { d: "m13 12 4-4" }],
  ["path", { d: "M10.9 7.25A3.99 3.99 0 0 0 4 10c0 .73.2 1.41.54 2" }]
];

// node_modules/lucide/dist/esm/icons/sandwich.js
var Sandwich = [
  ["path", { d: "m2.37 11.223 8.372-6.777a2 2 0 0 1 2.516 0l8.371 6.777" }],
  ["path", { d: "M21 15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-5.25" }],
  ["path", { d: "M3 15a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h9" }],
  ["path", { d: "m6.67 15 6.13 4.6a2 2 0 0 0 2.8-.4l3.15-4.2" }],
  ["rect", { width: "20", height: "4", x: "2", y: "11", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/satellite-dish.js
var SatelliteDish = [
  ["path", { d: "M4 10a7.31 7.31 0 0 0 10 10Z" }],
  ["path", { d: "m9 15 3-3" }],
  ["path", { d: "M17 13a6 6 0 0 0-6-6" }],
  ["path", { d: "M21 13A10 10 0 0 0 11 3" }]
];

// node_modules/lucide/dist/esm/icons/satellite.js
var Satellite = [
  ["path", { d: "M13 7 9 3 5 7l4 4" }],
  ["path", { d: "m17 11 4 4-4 4-4-4" }],
  ["path", { d: "m8 12 4 4 6-6-4-4Z" }],
  ["path", { d: "m16 8 3-3" }],
  ["path", { d: "M9 21a6 6 0 0 0-6-6" }]
];

// node_modules/lucide/dist/esm/icons/saudi-riyal.js
var SaudiRiyal = [
  ["path", { d: "m20 19.5-5.5 1.2" }],
  ["path", { d: "M14.5 4v11.22a1 1 0 0 0 1.242.97L20 15.2" }],
  ["path", { d: "m2.978 19.351 5.549-1.363A2 2 0 0 0 10 16V2" }],
  ["path", { d: "M20 10 4 13.5" }]
];

// node_modules/lucide/dist/esm/icons/save-all.js
var SaveAll = [
  ["path", { d: "M10 2v3a1 1 0 0 0 1 1h5" }],
  ["path", { d: "M18 18v-6a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v6" }],
  ["path", { d: "M18 22H4a2 2 0 0 1-2-2V6" }],
  [
    "path",
    {
      d: "M8 18a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9.172a2 2 0 0 1 1.414.586l2.828 2.828A2 2 0 0 1 22 6.828V16a2 2 0 0 1-2.01 2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/save-off.js
var SaveOff = [
  ["path", { d: "M13 13H8a1 1 0 0 0-1 1v7" }],
  ["path", { d: "M14 8h1" }],
  ["path", { d: "M17 21v-4" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M20.41 20.41A2 2 0 0 1 19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 .59-1.41" }],
  ["path", { d: "M29.5 11.5s5 5 4 5" }],
  ["path", { d: "M9 3h6.2a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V15" }]
];

// node_modules/lucide/dist/esm/icons/save.js
var Save = [
  [
    "path",
    {
      d: "M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"
    }
  ],
  ["path", { d: "M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" }],
  ["path", { d: "M7 3v4a1 1 0 0 0 1 1h7" }]
];

// node_modules/lucide/dist/esm/icons/scale-3d.js
var Scale3d = [
  ["path", { d: "M5 7v11a1 1 0 0 0 1 1h11" }],
  ["path", { d: "M5.293 18.707 11 13" }],
  ["circle", { cx: "19", cy: "19", r: "2" }],
  ["circle", { cx: "5", cy: "5", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/scale.js
var Scale = [
  ["path", { d: "m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" }],
  ["path", { d: "m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" }],
  ["path", { d: "M7 21h10" }],
  ["path", { d: "M12 3v18" }],
  ["path", { d: "M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2" }]
];

// node_modules/lucide/dist/esm/icons/scaling.js
var Scaling = [
  ["path", { d: "M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" }],
  ["path", { d: "M14 15H9v-5" }],
  ["path", { d: "M16 3h5v5" }],
  ["path", { d: "M21 3 9 15" }]
];

// node_modules/lucide/dist/esm/icons/scan-barcode.js
var ScanBarcode = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["path", { d: "M8 7v10" }],
  ["path", { d: "M12 7v10" }],
  ["path", { d: "M17 7v10" }]
];

// node_modules/lucide/dist/esm/icons/scan-eye.js
var ScanEye = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["circle", { cx: "12", cy: "12", r: "1" }],
  [
    "path",
    {
      d: "M18.944 12.33a1 1 0 0 0 0-.66 7.5 7.5 0 0 0-13.888 0 1 1 0 0 0 0 .66 7.5 7.5 0 0 0 13.888 0"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/scan-face.js
var ScanFace = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["path", { d: "M8 14s1.5 2 4 2 4-2 4-2" }],
  ["path", { d: "M9 9h.01" }],
  ["path", { d: "M15 9h.01" }]
];

// node_modules/lucide/dist/esm/icons/scan-heart.js
var ScanHeart = [
  [
    "path",
    {
      d: "M11.246 16.657a1 1 0 0 0 1.508 0l3.57-4.101A2.75 2.75 0 1 0 12 9.168a2.75 2.75 0 1 0-4.324 3.388z"
    }
  ],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }]
];

// node_modules/lucide/dist/esm/icons/scan-qr-code.js
var ScanQrCode = [
  ["path", { d: "M17 12v4a1 1 0 0 1-1 1h-4" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M17 8V7" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M7 17h.01" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["rect", { x: "7", y: "7", width: "5", height: "5", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/scan-line.js
var ScanLine = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["path", { d: "M7 12h10" }]
];

// node_modules/lucide/dist/esm/icons/scan-search.js
var ScanSearch = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["circle", { cx: "12", cy: "12", r: "3" }],
  ["path", { d: "m16 16-1.9-1.9" }]
];

// node_modules/lucide/dist/esm/icons/scan-text.js
var ScanText = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }],
  ["path", { d: "M7 8h8" }],
  ["path", { d: "M7 12h10" }],
  ["path", { d: "M7 16h6" }]
];

// node_modules/lucide/dist/esm/icons/scan.js
var Scan = [
  ["path", { d: "M3 7V5a2 2 0 0 1 2-2h2" }],
  ["path", { d: "M17 3h2a2 2 0 0 1 2 2v2" }],
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M7 21H5a2 2 0 0 1-2-2v-2" }]
];

// node_modules/lucide/dist/esm/icons/school.js
var School = [
  ["path", { d: "M14 22v-4a2 2 0 1 0-4 0v4" }],
  [
    "path",
    {
      d: "m18 10 3.447 1.724a1 1 0 0 1 .553.894V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7.382a1 1 0 0 1 .553-.894L6 10"
    }
  ],
  ["path", { d: "M18 5v17" }],
  ["path", { d: "m4 6 7.106-3.553a2 2 0 0 1 1.788 0L20 6" }],
  ["path", { d: "M6 5v17" }],
  ["circle", { cx: "12", cy: "9", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/scissors-line-dashed.js
var ScissorsLineDashed = [
  ["path", { d: "M5.42 9.42 8 12" }],
  ["circle", { cx: "4", cy: "8", r: "2" }],
  ["path", { d: "m14 6-8.58 8.58" }],
  ["circle", { cx: "4", cy: "16", r: "2" }],
  ["path", { d: "M10.8 14.8 14 18" }],
  ["path", { d: "M16 12h-2" }],
  ["path", { d: "M22 12h-2" }]
];

// node_modules/lucide/dist/esm/icons/scissors.js
var Scissors = [
  ["circle", { cx: "6", cy: "6", r: "3" }],
  ["path", { d: "M8.12 8.12 12 12" }],
  ["path", { d: "M20 4 8.12 15.88" }],
  ["circle", { cx: "6", cy: "18", r: "3" }],
  ["path", { d: "M14.8 14.8 20 20" }]
];

// node_modules/lucide/dist/esm/icons/screen-share-off.js
var ScreenShareOff = [
  ["path", { d: "M13 3H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3" }],
  ["path", { d: "M8 21h8" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "m22 3-5 5" }],
  ["path", { d: "m17 3 5 5" }]
];

// node_modules/lucide/dist/esm/icons/screen-share.js
var ScreenShare = [
  ["path", { d: "M13 3H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3" }],
  ["path", { d: "M8 21h8" }],
  ["path", { d: "M12 17v4" }],
  ["path", { d: "m17 8 5-5" }],
  ["path", { d: "M17 3h5v5" }]
];

// node_modules/lucide/dist/esm/icons/scroll-text.js
var ScrollText = [
  ["path", { d: "M15 12h-5" }],
  ["path", { d: "M15 8h-5" }],
  ["path", { d: "M19 17V5a2 2 0 0 0-2-2H4" }],
  [
    "path",
    {
      d: "M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/scroll.js
var Scroll = [
  ["path", { d: "M19 17V5a2 2 0 0 0-2-2H4" }],
  [
    "path",
    {
      d: "M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/search-check.js
var SearchCheck = [
  ["path", { d: "m8 11 2 2 4-4" }],
  ["circle", { cx: "11", cy: "11", r: "8" }],
  ["path", { d: "m21 21-4.3-4.3" }]
];

// node_modules/lucide/dist/esm/icons/search-code.js
var SearchCode = [
  ["path", { d: "m13 13.5 2-2.5-2-2.5" }],
  ["path", { d: "m21 21-4.3-4.3" }],
  ["path", { d: "M9 8.5 7 11l2 2.5" }],
  ["circle", { cx: "11", cy: "11", r: "8" }]
];

// node_modules/lucide/dist/esm/icons/search-slash.js
var SearchSlash = [
  ["path", { d: "m13.5 8.5-5 5" }],
  ["circle", { cx: "11", cy: "11", r: "8" }],
  ["path", { d: "m21 21-4.3-4.3" }]
];

// node_modules/lucide/dist/esm/icons/search-x.js
var SearchX = [
  ["path", { d: "m13.5 8.5-5 5" }],
  ["path", { d: "m8.5 8.5 5 5" }],
  ["circle", { cx: "11", cy: "11", r: "8" }],
  ["path", { d: "m21 21-4.3-4.3" }]
];

// node_modules/lucide/dist/esm/icons/section.js
var Section = [
  ["path", { d: "M16 5a4 3 0 0 0-8 0c0 4 8 3 8 7a4 3 0 0 1-8 0" }],
  ["path", { d: "M8 19a4 3 0 0 0 8 0c0-4-8-3-8-7a4 3 0 0 1 8 0" }]
];

// node_modules/lucide/dist/esm/icons/search.js
var Search = [
  ["path", { d: "m21 21-4.34-4.34" }],
  ["circle", { cx: "11", cy: "11", r: "8" }]
];

// node_modules/lucide/dist/esm/icons/send-horizontal.js
var SendHorizontal = [
  [
    "path",
    {
      d: "M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"
    }
  ],
  ["path", { d: "M6 12h16" }]
];

// node_modules/lucide/dist/esm/icons/send-to-back.js
var SendToBack = [
  ["rect", { x: "14", y: "14", width: "8", height: "8", rx: "2" }],
  ["rect", { x: "2", y: "2", width: "8", height: "8", rx: "2" }],
  ["path", { d: "M7 14v1a2 2 0 0 0 2 2h1" }],
  ["path", { d: "M14 7h1a2 2 0 0 1 2 2v1" }]
];

// node_modules/lucide/dist/esm/icons/send.js
var Send = [
  [
    "path",
    {
      d: "M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"
    }
  ],
  ["path", { d: "m21.854 2.147-10.94 10.939" }]
];

// node_modules/lucide/dist/esm/icons/separator-horizontal.js
var SeparatorHorizontal = [
  ["line", { x1: "3", x2: "21", y1: "12", y2: "12" }],
  ["polyline", { points: "8 8 12 4 16 8" }],
  ["polyline", { points: "16 16 12 20 8 16" }]
];

// node_modules/lucide/dist/esm/icons/separator-vertical.js
var SeparatorVertical = [
  ["line", { x1: "12", x2: "12", y1: "3", y2: "21" }],
  ["polyline", { points: "8 8 4 12 8 16" }],
  ["polyline", { points: "16 16 20 12 16 8" }]
];

// node_modules/lucide/dist/esm/icons/server-cog.js
var ServerCog = [
  ["path", { d: "m10.852 14.772-.383.923" }],
  ["path", { d: "M13.148 14.772a3 3 0 1 0-2.296-5.544l-.383-.923" }],
  ["path", { d: "m13.148 9.228.383-.923" }],
  ["path", { d: "m13.53 15.696-.382-.924a3 3 0 1 1-2.296-5.544" }],
  ["path", { d: "m14.772 10.852.923-.383" }],
  ["path", { d: "m14.772 13.148.923.383" }],
  ["path", { d: "M4.5 10H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-.5" }],
  ["path", { d: "M4.5 14H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-.5" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "M6 6h.01" }],
  ["path", { d: "m9.228 10.852-.923-.383" }],
  ["path", { d: "m9.228 13.148-.923.383" }]
];

// node_modules/lucide/dist/esm/icons/server-crash.js
var ServerCrash = [
  ["path", { d: "M6 10H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2" }],
  ["path", { d: "M6 14H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2" }],
  ["path", { d: "M6 6h.01" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "m13 6-4 6h6l-4 6" }]
];

// node_modules/lucide/dist/esm/icons/server-off.js
var ServerOff = [
  ["path", { d: "M7 2h13a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-5" }],
  ["path", { d: "M10 10 2.5 2.5C2 2 2 2.5 2 5v3a2 2 0 0 0 2 2h6z" }],
  ["path", { d: "M22 17v-1a2 2 0 0 0-2-2h-1" }],
  ["path", { d: "M4 14a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16.5l1-.5.5.5-8-8H4z" }],
  ["path", { d: "M6 18h.01" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/server.js
var Server = [
  ["rect", { width: "20", height: "8", x: "2", y: "2", rx: "2", ry: "2" }],
  ["rect", { width: "20", height: "8", x: "2", y: "14", rx: "2", ry: "2" }],
  ["line", { x1: "6", x2: "6.01", y1: "6", y2: "6" }],
  ["line", { x1: "6", x2: "6.01", y1: "18", y2: "18" }]
];

// node_modules/lucide/dist/esm/icons/settings-2.js
var Settings2 = [
  ["path", { d: "M20 7h-9" }],
  ["path", { d: "M14 17H5" }],
  ["circle", { cx: "17", cy: "17", r: "3" }],
  ["circle", { cx: "7", cy: "7", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/settings.js
var Settings = [
  [
    "path",
    {
      d: "M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"
    }
  ],
  ["circle", { cx: "12", cy: "12", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/shapes.js
var Shapes = [
  [
    "path",
    {
      d: "M8.3 10a.7.7 0 0 1-.626-1.079L11.4 3a.7.7 0 0 1 1.198-.043L16.3 8.9a.7.7 0 0 1-.572 1.1Z"
    }
  ],
  ["rect", { x: "3", y: "14", width: "7", height: "7", rx: "1" }],
  ["circle", { cx: "17.5", cy: "17.5", r: "3.5" }]
];

// node_modules/lucide/dist/esm/icons/share-2.js
var Share2 = [
  ["circle", { cx: "18", cy: "5", r: "3" }],
  ["circle", { cx: "6", cy: "12", r: "3" }],
  ["circle", { cx: "18", cy: "19", r: "3" }],
  ["line", { x1: "8.59", x2: "15.42", y1: "13.51", y2: "17.49" }],
  ["line", { x1: "15.41", x2: "8.59", y1: "6.51", y2: "10.49" }]
];

// node_modules/lucide/dist/esm/icons/sheet.js
var Sheet = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["line", { x1: "3", x2: "21", y1: "9", y2: "9" }],
  ["line", { x1: "3", x2: "21", y1: "15", y2: "15" }],
  ["line", { x1: "9", x2: "9", y1: "9", y2: "21" }],
  ["line", { x1: "15", x2: "15", y1: "9", y2: "21" }]
];

// node_modules/lucide/dist/esm/icons/share.js
var Share = [
  ["path", { d: "M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" }],
  ["polyline", { points: "16 6 12 2 8 6" }],
  ["line", { x1: "12", x2: "12", y1: "2", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/shell.js
var Shell = [
  [
    "path",
    {
      d: "M14 11a2 2 0 1 1-4 0 4 4 0 0 1 8 0 6 6 0 0 1-12 0 8 8 0 0 1 16 0 10 10 0 1 1-20 0 11.93 11.93 0 0 1 2.42-7.22 2 2 0 1 1 3.16 2.44"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/shield-alert.js
var ShieldAlert = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M12 8v4" }],
  ["path", { d: "M12 16h.01" }]
];

// node_modules/lucide/dist/esm/icons/shield-ban.js
var ShieldBan = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "m4.243 5.21 14.39 12.472" }]
];

// node_modules/lucide/dist/esm/icons/shield-check.js
var ShieldCheck = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "m9 12 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/shield-ellipsis.js
var ShieldEllipsis = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M8 12h.01" }],
  ["path", { d: "M12 12h.01" }],
  ["path", { d: "M16 12h.01" }]
];

// node_modules/lucide/dist/esm/icons/shield-half.js
var ShieldHalf = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M12 22V2" }]
];

// node_modules/lucide/dist/esm/icons/shield-minus.js
var ShieldMinus = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M9 12h6" }]
];

// node_modules/lucide/dist/esm/icons/shield-off.js
var ShieldOff = [
  ["path", { d: "m2 2 20 20" }],
  [
    "path",
    {
      d: "M5 5a1 1 0 0 0-1 1v7c0 5 3.5 7.5 7.67 8.94a1 1 0 0 0 .67.01c2.35-.82 4.48-1.97 5.9-3.71"
    }
  ],
  [
    "path",
    {
      d: "M9.309 3.652A12.252 12.252 0 0 0 11.24 2.28a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1v7a9.784 9.784 0 0 1-.08 1.264"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/shield-plus.js
var ShieldPlus = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M9 12h6" }],
  ["path", { d: "M12 9v6" }]
];

// node_modules/lucide/dist/esm/icons/shield-question.js
var ShieldQuestion = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3" }],
  ["path", { d: "M12 17h.01" }]
];

// node_modules/lucide/dist/esm/icons/shield-user.js
var ShieldUser = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "M6.376 18.91a6 6 0 0 1 11.249.003" }],
  ["circle", { cx: "12", cy: "11", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/shield-x.js
var ShieldX = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ],
  ["path", { d: "m14.5 9.5-5 5" }],
  ["path", { d: "m9.5 9.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/ship-wheel.js
var ShipWheel = [
  ["circle", { cx: "12", cy: "12", r: "8" }],
  ["path", { d: "M12 2v7.5" }],
  ["path", { d: "m19 5-5.23 5.23" }],
  ["path", { d: "M22 12h-7.5" }],
  ["path", { d: "m19 19-5.23-5.23" }],
  ["path", { d: "M12 14.5V22" }],
  ["path", { d: "M10.23 13.77 5 19" }],
  ["path", { d: "M9.5 12H2" }],
  ["path", { d: "M10.23 10.23 5 5" }],
  ["circle", { cx: "12", cy: "12", r: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/ship.js
var Ship = [
  ["path", { d: "M12 10.189V14" }],
  ["path", { d: "M12 2v3" }],
  ["path", { d: "M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" }],
  [
    "path",
    {
      d: "M19.38 20A11.6 11.6 0 0 0 21 14l-8.188-3.639a2 2 0 0 0-1.624 0L3 14a11.6 11.6 0 0 0 2.81 7.76"
    }
  ],
  [
    "path",
    {
      d: "M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/shield.js
var Shield = [
  [
    "path",
    {
      d: "M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/shirt.js
var Shirt = [
  [
    "path",
    {
      d: "M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/shopping-basket.js
var ShoppingBasket = [
  ["path", { d: "m15 11-1 9" }],
  ["path", { d: "m19 11-4-7" }],
  ["path", { d: "M2 11h20" }],
  ["path", { d: "m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4" }],
  ["path", { d: "M4.5 15.5h15" }],
  ["path", { d: "m5 11 4-7" }],
  ["path", { d: "m9 11 1 9" }]
];

// node_modules/lucide/dist/esm/icons/shopping-bag.js
var ShoppingBag = [
  ["path", { d: "M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" }],
  ["path", { d: "M3 6h18" }],
  ["path", { d: "M16 10a4 4 0 0 1-8 0" }]
];

// node_modules/lucide/dist/esm/icons/shopping-cart.js
var ShoppingCart = [
  ["circle", { cx: "8", cy: "21", r: "1" }],
  ["circle", { cx: "19", cy: "21", r: "1" }],
  [
    "path",
    { d: "M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" }
  ]
];

// node_modules/lucide/dist/esm/icons/shovel.js
var Shovel = [
  ["path", { d: "M2 22v-5l5-5 5 5-5 5z" }],
  ["path", { d: "M9.5 14.5 16 8" }],
  ["path", { d: "m17 2 5 5-.5.5a3.53 3.53 0 0 1-5 0s0 0 0 0a3.53 3.53 0 0 1 0-5L17 2" }]
];

// node_modules/lucide/dist/esm/icons/shredder.js
var Shredder = [
  ["path", { d: "M10 22v-5" }],
  ["path", { d: "M14 19v-2" }],
  ["path", { d: "M14 2v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M18 20v-3" }],
  ["path", { d: "M2 13h20" }],
  ["path", { d: "M20 13V7l-5-5H6a2 2 0 0 0-2 2v9" }],
  ["path", { d: "M6 20v-3" }]
];

// node_modules/lucide/dist/esm/icons/shower-head.js
var ShowerHead = [
  ["path", { d: "m4 4 2.5 2.5" }],
  ["path", { d: "M13.5 6.5a4.95 4.95 0 0 0-7 7" }],
  ["path", { d: "M15 5 5 15" }],
  ["path", { d: "M14 17v.01" }],
  ["path", { d: "M10 16v.01" }],
  ["path", { d: "M13 13v.01" }],
  ["path", { d: "M16 10v.01" }],
  ["path", { d: "M11 20v.01" }],
  ["path", { d: "M17 14v.01" }],
  ["path", { d: "M20 11v.01" }]
];

// node_modules/lucide/dist/esm/icons/shrimp.js
var Shrimp = [
  ["path", { d: "M11 12h.01" }],
  ["path", { d: "M13 22c.5-.5 1.12-1 2.5-1-1.38 0-2-.5-2.5-1" }],
  [
    "path",
    {
      d: "M14 2a3.28 3.28 0 0 1-3.227 1.798l-6.17-.561A2.387 2.387 0 1 0 4.387 8H15.5a1 1 0 0 1 0 13 1 1 0 0 0 0-5H12a7 7 0 0 1-7-7V8"
    }
  ],
  ["path", { d: "M14 8a8.5 8.5 0 0 1 0 8" }],
  ["path", { d: "M16 16c2 0 4.5-4 4-6" }]
];

// node_modules/lucide/dist/esm/icons/shrink.js
var Shrink = [
  ["path", { d: "m15 15 6 6m-6-6v4.8m0-4.8h4.8" }],
  ["path", { d: "M9 19.8V15m0 0H4.2M9 15l-6 6" }],
  ["path", { d: "M15 4.2V9m0 0h4.8M15 9l6-6" }],
  ["path", { d: "M9 4.2V9m0 0H4.2M9 9 3 3" }]
];

// node_modules/lucide/dist/esm/icons/shrub.js
var Shrub = [
  ["path", { d: "M12 22v-7l-2-2" }],
  ["path", { d: "M17 8v.8A6 6 0 0 1 13.8 20H10A6.5 6.5 0 0 1 7 8a5 5 0 0 1 10 0Z" }],
  ["path", { d: "m14 14-2 2" }]
];

// node_modules/lucide/dist/esm/icons/shuffle.js
var Shuffle = [
  ["path", { d: "m18 14 4 4-4 4" }],
  ["path", { d: "m18 2 4 4-4 4" }],
  ["path", { d: "M2 18h1.973a4 4 0 0 0 3.3-1.7l5.454-8.6a4 4 0 0 1 3.3-1.7H22" }],
  ["path", { d: "M2 6h1.972a4 4 0 0 1 3.6 2.2" }],
  ["path", { d: "M22 18h-6.041a4 4 0 0 1-3.3-1.8l-.359-.45" }]
];

// node_modules/lucide/dist/esm/icons/sigma.js
var Sigma = [
  [
    "path",
    {
      d: "M18 7V5a1 1 0 0 0-1-1H6.5a.5.5 0 0 0-.4.8l4.5 6a2 2 0 0 1 0 2.4l-4.5 6a.5.5 0 0 0 .4.8H17a1 1 0 0 0 1-1v-2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/signal-high.js
var SignalHigh = [
  ["path", { d: "M2 20h.01" }],
  ["path", { d: "M7 20v-4" }],
  ["path", { d: "M12 20v-8" }],
  ["path", { d: "M17 20V8" }]
];

// node_modules/lucide/dist/esm/icons/signal-medium.js
var SignalMedium = [
  ["path", { d: "M2 20h.01" }],
  ["path", { d: "M7 20v-4" }],
  ["path", { d: "M12 20v-8" }]
];

// node_modules/lucide/dist/esm/icons/signal-low.js
var SignalLow = [
  ["path", { d: "M2 20h.01" }],
  ["path", { d: "M7 20v-4" }]
];

// node_modules/lucide/dist/esm/icons/signal-zero.js
var SignalZero = [["path", { d: "M2 20h.01" }]];

// node_modules/lucide/dist/esm/icons/signature.js
var Signature = [
  [
    "path",
    {
      d: "m21 17-2.156-1.868A.5.5 0 0 0 18 15.5v.5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1c0-2.545-3.991-3.97-8.5-4a1 1 0 0 0 0 5c4.153 0 4.745-11.295 5.708-13.5a2.5 2.5 0 1 1 3.31 3.284"
    }
  ],
  ["path", { d: "M3 21h18" }]
];

// node_modules/lucide/dist/esm/icons/signal.js
var Signal = [
  ["path", { d: "M2 20h.01" }],
  ["path", { d: "M7 20v-4" }],
  ["path", { d: "M12 20v-8" }],
  ["path", { d: "M17 20V8" }],
  ["path", { d: "M22 4v16" }]
];

// node_modules/lucide/dist/esm/icons/signpost-big.js
var SignpostBig = [
  ["path", { d: "M10 9H4L2 7l2-2h6" }],
  ["path", { d: "M14 5h6l2 2-2 2h-6" }],
  ["path", { d: "M10 22V4a2 2 0 1 1 4 0v18" }],
  ["path", { d: "M8 22h8" }]
];

// node_modules/lucide/dist/esm/icons/signpost.js
var Signpost = [
  ["path", { d: "M12 13v8" }],
  ["path", { d: "M12 3v3" }],
  [
    "path",
    {
      d: "M18 6a2 2 0 0 1 1.387.56l2.307 2.22a1 1 0 0 1 0 1.44l-2.307 2.22A2 2 0 0 1 18 13H6a2 2 0 0 1-1.387-.56l-2.306-2.22a1 1 0 0 1 0-1.44l2.306-2.22A2 2 0 0 1 6 6z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/siren.js
var Siren = [
  ["path", { d: "M7 18v-6a5 5 0 1 1 10 0v6" }],
  ["path", { d: "M5 21a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2z" }],
  ["path", { d: "M21 12h1" }],
  ["path", { d: "M18.5 4.5 18 5" }],
  ["path", { d: "M2 12h1" }],
  ["path", { d: "M12 2v1" }],
  ["path", { d: "m4.929 4.929.707.707" }],
  ["path", { d: "M12 12v6" }]
];

// node_modules/lucide/dist/esm/icons/skip-forward.js
var SkipForward = [
  ["polygon", { points: "5 4 15 12 5 20 5 4" }],
  ["line", { x1: "19", x2: "19", y1: "5", y2: "19" }]
];

// node_modules/lucide/dist/esm/icons/skip-back.js
var SkipBack = [
  ["polygon", { points: "19 20 9 12 19 4 19 20" }],
  ["line", { x1: "5", x2: "5", y1: "19", y2: "5" }]
];

// node_modules/lucide/dist/esm/icons/skull.js
var Skull = [
  ["path", { d: "m12.5 17-.5-1-.5 1h1z" }],
  [
    "path",
    {
      d: "M15 22a1 1 0 0 0 1-1v-1a2 2 0 0 0 1.56-3.25 8 8 0 1 0-11.12 0A2 2 0 0 0 8 20v1a1 1 0 0 0 1 1z"
    }
  ],
  ["circle", { cx: "15", cy: "12", r: "1" }],
  ["circle", { cx: "9", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/slack.js
var Slack = [
  ["rect", { width: "3", height: "8", x: "13", y: "2", rx: "1.5" }],
  ["path", { d: "M19 8.5V10h1.5A1.5 1.5 0 1 0 19 8.5" }],
  ["rect", { width: "3", height: "8", x: "8", y: "14", rx: "1.5" }],
  ["path", { d: "M5 15.5V14H3.5A1.5 1.5 0 1 0 5 15.5" }],
  ["rect", { width: "8", height: "3", x: "14", y: "13", rx: "1.5" }],
  ["path", { d: "M15.5 19H14v1.5a1.5 1.5 0 1 0 1.5-1.5" }],
  ["rect", { width: "8", height: "3", x: "2", y: "8", rx: "1.5" }],
  ["path", { d: "M8.5 5H10V3.5A1.5 1.5 0 1 0 8.5 5" }]
];

// node_modules/lucide/dist/esm/icons/slash.js
var Slash = [["path", { d: "M22 2 2 22" }]];

// node_modules/lucide/dist/esm/icons/slice.js
var Slice = [
  [
    "path",
    {
      d: "M11 16.586V19a1 1 0 0 1-1 1H2L18.37 3.63a1 1 0 1 1 3 3l-9.663 9.663a1 1 0 0 1-1.414 0L8 14"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/sliders-horizontal.js
var SlidersHorizontal = [
  ["line", { x1: "21", x2: "14", y1: "4", y2: "4" }],
  ["line", { x1: "10", x2: "3", y1: "4", y2: "4" }],
  ["line", { x1: "21", x2: "12", y1: "12", y2: "12" }],
  ["line", { x1: "8", x2: "3", y1: "12", y2: "12" }],
  ["line", { x1: "21", x2: "16", y1: "20", y2: "20" }],
  ["line", { x1: "12", x2: "3", y1: "20", y2: "20" }],
  ["line", { x1: "14", x2: "14", y1: "2", y2: "6" }],
  ["line", { x1: "8", x2: "8", y1: "10", y2: "14" }],
  ["line", { x1: "16", x2: "16", y1: "18", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/sliders-vertical.js
var SlidersVertical = [
  ["line", { x1: "4", x2: "4", y1: "21", y2: "14" }],
  ["line", { x1: "4", x2: "4", y1: "10", y2: "3" }],
  ["line", { x1: "12", x2: "12", y1: "21", y2: "12" }],
  ["line", { x1: "12", x2: "12", y1: "8", y2: "3" }],
  ["line", { x1: "20", x2: "20", y1: "21", y2: "16" }],
  ["line", { x1: "20", x2: "20", y1: "12", y2: "3" }],
  ["line", { x1: "2", x2: "6", y1: "14", y2: "14" }],
  ["line", { x1: "10", x2: "14", y1: "8", y2: "8" }],
  ["line", { x1: "18", x2: "22", y1: "16", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/smartphone-charging.js
var SmartphoneCharging = [
  ["rect", { width: "14", height: "20", x: "5", y: "2", rx: "2", ry: "2" }],
  ["path", { d: "M12.667 8 10 12h4l-2.667 4" }]
];

// node_modules/lucide/dist/esm/icons/smartphone-nfc.js
var SmartphoneNfc = [
  ["rect", { width: "7", height: "12", x: "2", y: "6", rx: "1" }],
  ["path", { d: "M13 8.32a7.43 7.43 0 0 1 0 7.36" }],
  ["path", { d: "M16.46 6.21a11.76 11.76 0 0 1 0 11.58" }],
  ["path", { d: "M19.91 4.1a15.91 15.91 0 0 1 .01 15.8" }]
];

// node_modules/lucide/dist/esm/icons/smartphone.js
var Smartphone = [
  ["rect", { width: "14", height: "20", x: "5", y: "2", rx: "2", ry: "2" }],
  ["path", { d: "M12 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/smile-plus.js
var SmilePlus = [
  ["path", { d: "M22 11v1a10 10 0 1 1-9-10" }],
  ["path", { d: "M8 14s1.5 2 4 2 4-2 4-2" }],
  ["line", { x1: "9", x2: "9.01", y1: "9", y2: "9" }],
  ["line", { x1: "15", x2: "15.01", y1: "9", y2: "9" }],
  ["path", { d: "M16 5h6" }],
  ["path", { d: "M19 2v6" }]
];

// node_modules/lucide/dist/esm/icons/smile.js
var Smile = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["path", { d: "M8 14s1.5 2 4 2 4-2 4-2" }],
  ["line", { x1: "9", x2: "9.01", y1: "9", y2: "9" }],
  ["line", { x1: "15", x2: "15.01", y1: "9", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/snail.js
var Snail = [
  ["path", { d: "M2 13a6 6 0 1 0 12 0 4 4 0 1 0-8 0 2 2 0 0 0 4 0" }],
  ["circle", { cx: "10", cy: "13", r: "8" }],
  ["path", { d: "M2 21h12c4.4 0 8-3.6 8-8V7a2 2 0 1 0-4 0v6" }],
  ["path", { d: "M18 3 19.1 5.2" }],
  ["path", { d: "M22 3 20.9 5.2" }]
];

// node_modules/lucide/dist/esm/icons/snowflake.js
var Snowflake = [
  ["path", { d: "m10 20-1.25-2.5L6 18" }],
  ["path", { d: "M10 4 8.75 6.5 6 6" }],
  ["path", { d: "m14 20 1.25-2.5L18 18" }],
  ["path", { d: "m14 4 1.25 2.5L18 6" }],
  ["path", { d: "m17 21-3-6h-4" }],
  ["path", { d: "m17 3-3 6 1.5 3" }],
  ["path", { d: "M2 12h6.5L10 9" }],
  ["path", { d: "m20 10-1.5 2 1.5 2" }],
  ["path", { d: "M22 12h-6.5L14 15" }],
  ["path", { d: "m4 10 1.5 2L4 14" }],
  ["path", { d: "m7 21 3-6-1.5-3" }],
  ["path", { d: "m7 3 3 6h4" }]
];

// node_modules/lucide/dist/esm/icons/soap-dispenser-droplet.js
var SoapDispenserDroplet = [
  ["path", { d: "M10.5 2v4" }],
  ["path", { d: "M14 2H7a2 2 0 0 0-2 2" }],
  [
    "path",
    {
      d: "M19.29 14.76A6.67 6.67 0 0 1 17 11a6.6 6.6 0 0 1-2.29 3.76c-1.15.92-1.71 2.04-1.71 3.19 0 2.22 1.8 4.05 4 4.05s4-1.83 4-4.05c0-1.16-.57-2.26-1.71-3.19"
    }
  ],
  ["path", { d: "M9.607 21H6a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h7V7a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3" }]
];

// node_modules/lucide/dist/esm/icons/sofa.js
var Sofa = [
  ["path", { d: "M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3" }],
  [
    "path",
    {
      d: "M2 16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5a2 2 0 0 0-4 0v1.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5V11a2 2 0 0 0-4 0z"
    }
  ],
  ["path", { d: "M4 18v2" }],
  ["path", { d: "M20 18v2" }],
  ["path", { d: "M12 4v9" }]
];

// node_modules/lucide/dist/esm/icons/soup.js
var Soup = [
  ["path", { d: "M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9Z" }],
  ["path", { d: "M7 21h10" }],
  ["path", { d: "M19.5 12 22 6" }],
  ["path", { d: "M16.25 3c.27.1.8.53.75 1.36-.06.83-.93 1.2-1 2.02-.05.78.34 1.24.73 1.62" }],
  ["path", { d: "M11.25 3c.27.1.8.53.74 1.36-.05.83-.93 1.2-.98 2.02-.06.78.33 1.24.72 1.62" }],
  ["path", { d: "M6.25 3c.27.1.8.53.75 1.36-.06.83-.93 1.2-1 2.02-.05.78.34 1.24.74 1.62" }]
];

// node_modules/lucide/dist/esm/icons/space.js
var Space = [["path", { d: "M22 17v1c0 .5-.5 1-1 1H3c-.5 0-1-.5-1-1v-1" }]];

// node_modules/lucide/dist/esm/icons/spade.js
var Spade = [
  [
    "path",
    {
      d: "M5 9c-1.5 1.5-3 3.2-3 5.5A5.5 5.5 0 0 0 7.5 20c1.8 0 3-.5 4.5-2 1.5 1.5 2.7 2 4.5 2a5.5 5.5 0 0 0 5.5-5.5c0-2.3-1.5-4-3-5.5l-7-7-7 7Z"
    }
  ],
  ["path", { d: "M12 18v4" }]
];

// node_modules/lucide/dist/esm/icons/sparkles.js
var Sparkles = [
  [
    "path",
    {
      d: "M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"
    }
  ],
  ["path", { d: "M20 3v4" }],
  ["path", { d: "M22 5h-4" }],
  ["path", { d: "M4 17v2" }],
  ["path", { d: "M5 18H3" }]
];

// node_modules/lucide/dist/esm/icons/sparkle.js
var Sparkle = [
  [
    "path",
    {
      d: "M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/speaker.js
var Speaker = [
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2" }],
  ["path", { d: "M12 6h.01" }],
  ["circle", { cx: "12", cy: "14", r: "4" }],
  ["path", { d: "M12 14h.01" }]
];

// node_modules/lucide/dist/esm/icons/speech.js
var Speech = [
  [
    "path",
    {
      d: "M8.8 20v-4.1l1.9.2a2.3 2.3 0 0 0 2.164-2.1V8.3A5.37 5.37 0 0 0 2 8.25c0 2.8.656 3.054 1 4.55a5.77 5.77 0 0 1 .029 2.758L2 20"
    }
  ],
  ["path", { d: "M19.8 17.8a7.5 7.5 0 0 0 .003-10.603" }],
  ["path", { d: "M17 15a3.5 3.5 0 0 0-.025-4.975" }]
];

// node_modules/lucide/dist/esm/icons/spell-check-2.js
var SpellCheck2 = [
  ["path", { d: "m6 16 6-12 6 12" }],
  ["path", { d: "M8 12h8" }],
  [
    "path",
    {
      d: "M4 21c1.1 0 1.1-1 2.3-1s1.1 1 2.3 1c1.1 0 1.1-1 2.3-1 1.1 0 1.1 1 2.3 1 1.1 0 1.1-1 2.3-1 1.1 0 1.1 1 2.3 1 1.1 0 1.1-1 2.3-1"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/spline-pointer.js
var SplinePointer = [
  [
    "path",
    {
      d: "M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z"
    }
  ],
  ["path", { d: "M5 17A12 12 0 0 1 17 5" }],
  ["circle", { cx: "19", cy: "5", r: "2" }],
  ["circle", { cx: "5", cy: "19", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/split.js
var Split = [
  ["path", { d: "M16 3h5v5" }],
  ["path", { d: "M8 3H3v5" }],
  ["path", { d: "M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3" }],
  ["path", { d: "m15 9 6-6" }]
];

// node_modules/lucide/dist/esm/icons/spell-check.js
var SpellCheck = [
  ["path", { d: "m6 16 6-12 6 12" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "m16 20 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/spline.js
var Spline = [
  ["circle", { cx: "19", cy: "5", r: "2" }],
  ["circle", { cx: "5", cy: "19", r: "2" }],
  ["path", { d: "M5 17A12 12 0 0 1 17 5" }]
];

// node_modules/lucide/dist/esm/icons/sprout.js
var Sprout = [
  ["path", { d: "M7 20h10" }],
  ["path", { d: "M10 20c5.5-2.5.8-6.4 3-10" }],
  [
    "path",
    {
      d: "M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"
    }
  ],
  [
    "path",
    { d: "M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z" }
  ]
];

// node_modules/lucide/dist/esm/icons/spray-can.js
var SprayCan = [
  ["path", { d: "M3 3h.01" }],
  ["path", { d: "M7 5h.01" }],
  ["path", { d: "M11 7h.01" }],
  ["path", { d: "M3 7h.01" }],
  ["path", { d: "M7 9h.01" }],
  ["path", { d: "M3 11h.01" }],
  ["rect", { width: "4", height: "4", x: "15", y: "5" }],
  ["path", { d: "m19 9 2 2v10c0 .6-.4 1-1 1h-6c-.6 0-1-.4-1-1V11l2-2" }],
  ["path", { d: "m13 14 8-2" }],
  ["path", { d: "m13 19 8-2" }]
];

// node_modules/lucide/dist/esm/icons/square-activity.js
var SquareActivity = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M17 12h-2l-2 5-2-10-2 5H7" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-down-left.js
var SquareArrowDownLeft = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m16 8-8 8" }],
  ["path", { d: "M16 16H8V8" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-down-right.js
var SquareArrowDownRight = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m8 8 8 8" }],
  ["path", { d: "M16 8v8H8" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-down.js
var SquareArrowDown = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M12 8v8" }],
  ["path", { d: "m8 12 4 4 4-4" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-left.js
var SquareArrowLeft = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m12 8-4 4 4 4" }],
  ["path", { d: "M16 12H8" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-out-down-left.js
var SquareArrowOutDownLeft = [
  ["path", { d: "M13 21h6a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6" }],
  ["path", { d: "m3 21 9-9" }],
  ["path", { d: "M9 21H3v-6" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-out-up-left.js
var SquareArrowOutUpLeft = [
  ["path", { d: "M13 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6" }],
  ["path", { d: "m3 3 9 9" }],
  ["path", { d: "M3 9V3h6" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-out-up-right.js
var SquareArrowOutUpRight = [
  ["path", { d: "M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6" }],
  ["path", { d: "m21 3-9 9" }],
  ["path", { d: "M15 3h6v6" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-out-down-right.js
var SquareArrowOutDownRight = [
  ["path", { d: "M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" }],
  ["path", { d: "m21 21-9-9" }],
  ["path", { d: "M21 15v6h-6" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-right.js
var SquareArrowRight = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "m12 16 4-4-4-4" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-up-left.js
var SquareArrowUpLeft = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 16V8h8" }],
  ["path", { d: "M16 16 8 8" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-up-right.js
var SquareArrowUpRight = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 8h8v8" }],
  ["path", { d: "m8 16 8-8" }]
];

// node_modules/lucide/dist/esm/icons/square-arrow-up.js
var SquareArrowUp = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m16 12-4-4-4 4" }],
  ["path", { d: "M12 16V8" }]
];

// node_modules/lucide/dist/esm/icons/square-asterisk.js
var SquareAsterisk = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M12 8v8" }],
  ["path", { d: "m8.5 14 7-4" }],
  ["path", { d: "m8.5 10 7 4" }]
];

// node_modules/lucide/dist/esm/icons/square-bottom-dashed-scissors.js
var SquareBottomDashedScissors = [
  ["path", { d: "M4 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2" }],
  ["path", { d: "M10 22H8" }],
  ["path", { d: "M16 22h-2" }],
  ["circle", { cx: "8", cy: "8", r: "2" }],
  ["path", { d: "M9.414 9.414 12 12" }],
  ["path", { d: "M14.8 14.8 18 18" }],
  ["circle", { cx: "8", cy: "16", r: "2" }],
  ["path", { d: "m18 6-8.586 8.586" }]
];

// node_modules/lucide/dist/esm/icons/square-chart-gantt.js
var SquareChartGantt = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 8h7" }],
  ["path", { d: "M8 12h6" }],
  ["path", { d: "M11 16h5" }]
];

// node_modules/lucide/dist/esm/icons/square-check-big.js
var SquareCheckBig = [
  ["path", { d: "M21 10.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12.5" }],
  ["path", { d: "m9 11 3 3L22 4" }]
];

// node_modules/lucide/dist/esm/icons/square-check.js
var SquareCheck = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m9 12 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/square-chevron-down.js
var SquareChevronDown = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m16 10-4 4-4-4" }]
];

// node_modules/lucide/dist/esm/icons/square-chevron-left.js
var SquareChevronLeft = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m14 16-4-4 4-4" }]
];

// node_modules/lucide/dist/esm/icons/square-chevron-right.js
var SquareChevronRight = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m10 8 4 4-4 4" }]
];

// node_modules/lucide/dist/esm/icons/square-chevron-up.js
var SquareChevronUp = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m8 14 4-4 4 4" }]
];

// node_modules/lucide/dist/esm/icons/square-code.js
var SquareCode = [
  ["path", { d: "M10 9.5 8 12l2 2.5" }],
  ["path", { d: "m14 9.5 2 2.5-2 2.5" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/square-dashed-bottom.js
var SquareDashedBottom = [
  ["path", { d: "M5 21a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2" }],
  ["path", { d: "M9 21h1" }],
  ["path", { d: "M14 21h1" }]
];

// node_modules/lucide/dist/esm/icons/square-dashed-bottom-code.js
var SquareDashedBottomCode = [
  ["path", { d: "M10 9.5 8 12l2 2.5" }],
  ["path", { d: "M14 21h1" }],
  ["path", { d: "m14 9.5 2 2.5-2 2.5" }],
  ["path", { d: "M5 21a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2" }],
  ["path", { d: "M9 21h1" }]
];

// node_modules/lucide/dist/esm/icons/square-dashed-kanban.js
var SquareDashedKanban = [
  ["path", { d: "M8 7v7" }],
  ["path", { d: "M12 7v4" }],
  ["path", { d: "M16 7v9" }],
  ["path", { d: "M5 3a2 2 0 0 0-2 2" }],
  ["path", { d: "M9 3h1" }],
  ["path", { d: "M14 3h1" }],
  ["path", { d: "M19 3a2 2 0 0 1 2 2" }],
  ["path", { d: "M21 9v1" }],
  ["path", { d: "M21 14v1" }],
  ["path", { d: "M21 19a2 2 0 0 1-2 2" }],
  ["path", { d: "M14 21h1" }],
  ["path", { d: "M9 21h1" }],
  ["path", { d: "M5 21a2 2 0 0 1-2-2" }],
  ["path", { d: "M3 14v1" }],
  ["path", { d: "M3 9v1" }]
];

// node_modules/lucide/dist/esm/icons/square-dashed-mouse-pointer.js
var SquareDashedMousePointer = [
  [
    "path",
    {
      d: "M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z"
    }
  ],
  ["path", { d: "M5 3a2 2 0 0 0-2 2" }],
  ["path", { d: "M19 3a2 2 0 0 1 2 2" }],
  ["path", { d: "M5 21a2 2 0 0 1-2-2" }],
  ["path", { d: "M9 3h1" }],
  ["path", { d: "M9 21h2" }],
  ["path", { d: "M14 3h1" }],
  ["path", { d: "M3 9v1" }],
  ["path", { d: "M21 9v2" }],
  ["path", { d: "M3 14v1" }]
];

// node_modules/lucide/dist/esm/icons/square-dashed.js
var SquareDashed = [
  ["path", { d: "M5 3a2 2 0 0 0-2 2" }],
  ["path", { d: "M19 3a2 2 0 0 1 2 2" }],
  ["path", { d: "M21 19a2 2 0 0 1-2 2" }],
  ["path", { d: "M5 21a2 2 0 0 1-2-2" }],
  ["path", { d: "M9 3h1" }],
  ["path", { d: "M9 21h1" }],
  ["path", { d: "M14 3h1" }],
  ["path", { d: "M14 21h1" }],
  ["path", { d: "M3 9v1" }],
  ["path", { d: "M21 9v1" }],
  ["path", { d: "M3 14v1" }],
  ["path", { d: "M21 14v1" }]
];

// node_modules/lucide/dist/esm/icons/square-divide.js
var SquareDivide = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["line", { x1: "8", x2: "16", y1: "12", y2: "12" }],
  ["line", { x1: "12", x2: "12", y1: "16", y2: "16" }],
  ["line", { x1: "12", x2: "12", y1: "8", y2: "8" }]
];

// node_modules/lucide/dist/esm/icons/square-dot.js
var SquareDot = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["circle", { cx: "12", cy: "12", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/square-equal.js
var SquareEqual = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 10h10" }],
  ["path", { d: "M7 14h10" }]
];

// node_modules/lucide/dist/esm/icons/square-function.js
var SquareFunction = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "M9 17c2 0 2.8-1 2.8-2.8V10c0-2 1-3.3 3.2-3" }],
  ["path", { d: "M9 11.2h5.7" }]
];

// node_modules/lucide/dist/esm/icons/square-kanban.js
var SquareKanban = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 7v7" }],
  ["path", { d: "M12 7v4" }],
  ["path", { d: "M16 7v9" }]
];

// node_modules/lucide/dist/esm/icons/square-library.js
var SquareLibrary = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 7v10" }],
  ["path", { d: "M11 7v10" }],
  ["path", { d: "m15 7 2 10" }]
];

// node_modules/lucide/dist/esm/icons/square-m.js
var SquareM = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 16V8l4 4 4-4v8" }]
];

// node_modules/lucide/dist/esm/icons/square-menu.js
var SquareMenu = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 8h10" }],
  ["path", { d: "M7 12h10" }],
  ["path", { d: "M7 16h10" }]
];

// node_modules/lucide/dist/esm/icons/square-minus.js
var SquareMinus = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 12h8" }]
];

// node_modules/lucide/dist/esm/icons/square-mouse-pointer.js
var SquareMousePointer = [
  [
    "path",
    {
      d: "M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z"
    }
  ],
  ["path", { d: "M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" }]
];

// node_modules/lucide/dist/esm/icons/square-parking-off.js
var SquareParkingOff = [
  ["path", { d: "M3.6 3.6A2 2 0 0 1 5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-.59 1.41" }],
  ["path", { d: "M3 8.7V19a2 2 0 0 0 2 2h10.3" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M13 13a3 3 0 1 0 0-6H9v2" }],
  ["path", { d: "M9 17v-2.3" }]
];

// node_modules/lucide/dist/esm/icons/square-parking.js
var SquareParking = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M9 17V7h4a3 3 0 0 1 0 6H9" }]
];

// node_modules/lucide/dist/esm/icons/square-pen.js
var SquarePen = [
  ["path", { d: "M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" }],
  [
    "path",
    {
      d: "M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/square-percent.js
var SquarePercent = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "M9 9h.01" }],
  ["path", { d: "M15 15h.01" }]
];

// node_modules/lucide/dist/esm/icons/square-pi.js
var SquarePi = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 7h10" }],
  ["path", { d: "M10 7v10" }],
  ["path", { d: "M16 17a2 2 0 0 1-2-2V7" }]
];

// node_modules/lucide/dist/esm/icons/square-pilcrow.js
var SquarePilcrow = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M12 12H9.5a2.5 2.5 0 0 1 0-5H17" }],
  ["path", { d: "M12 7v10" }],
  ["path", { d: "M16 7v10" }]
];

// node_modules/lucide/dist/esm/icons/square-play.js
var SquarePlay = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "m9 8 6 4-6 4Z" }]
];

// node_modules/lucide/dist/esm/icons/square-plus.js
var SquarePlus = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M8 12h8" }],
  ["path", { d: "M12 8v8" }]
];

// node_modules/lucide/dist/esm/icons/square-power.js
var SquarePower = [
  ["path", { d: "M12 7v4" }],
  ["path", { d: "M7.998 9.003a5 5 0 1 0 8-.005" }],
  ["rect", { x: "3", y: "3", width: "18", height: "18", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/square-radical.js
var SquareRadical = [
  ["path", { d: "M7 12h2l2 5 2-10h4" }],
  ["rect", { x: "3", y: "3", width: "18", height: "18", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/square-round-corner.js
var SquareRoundCorner = [
  ["path", { d: "M21 11a8 8 0 0 0-8-8" }],
  ["path", { d: "M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" }]
];

// node_modules/lucide/dist/esm/icons/square-scissors.js
var SquareScissors = [
  ["rect", { width: "20", height: "20", x: "2", y: "2", rx: "2" }],
  ["circle", { cx: "8", cy: "8", r: "2" }],
  ["path", { d: "M9.414 9.414 12 12" }],
  ["path", { d: "M14.8 14.8 18 18" }],
  ["circle", { cx: "8", cy: "16", r: "2" }],
  ["path", { d: "m18 6-8.586 8.586" }]
];

// node_modules/lucide/dist/esm/icons/square-sigma.js
var SquareSigma = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M16 8.9V7H8l4 5-4 5h8v-1.9" }]
];

// node_modules/lucide/dist/esm/icons/square-slash.js
var SquareSlash = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["line", { x1: "9", x2: "15", y1: "15", y2: "9" }]
];

// node_modules/lucide/dist/esm/icons/square-split-horizontal.js
var SquareSplitHorizontal = [
  ["path", { d: "M8 19H5c-1 0-2-1-2-2V7c0-1 1-2 2-2h3" }],
  ["path", { d: "M16 5h3c1 0 2 1 2 2v10c0 1-1 2-2 2h-3" }],
  ["line", { x1: "12", x2: "12", y1: "4", y2: "20" }]
];

// node_modules/lucide/dist/esm/icons/square-split-vertical.js
var SquareSplitVertical = [
  ["path", { d: "M5 8V5c0-1 1-2 2-2h10c1 0 2 1 2 2v3" }],
  ["path", { d: "M19 16v3c0 1-1 2-2 2H7c-1 0-2-1-2-2v-3" }],
  ["line", { x1: "4", x2: "20", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/square-square.js
var SquareSquare = [
  ["rect", { x: "3", y: "3", width: "18", height: "18", rx: "2" }],
  ["rect", { x: "8", y: "8", width: "8", height: "8", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/square-stack.js
var SquareStack = [
  ["path", { d: "M4 10c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h4c1.1 0 2 .9 2 2" }],
  ["path", { d: "M10 16c-1.1 0-2-.9-2-2v-4c0-1.1.9-2 2-2h4c1.1 0 2 .9 2 2" }],
  ["rect", { width: "8", height: "8", x: "14", y: "14", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/square-user-round.js
var SquareUserRound = [
  ["path", { d: "M18 21a6 6 0 0 0-12 0" }],
  ["circle", { cx: "12", cy: "11", r: "4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/square-terminal.js
var SquareTerminal = [
  ["path", { d: "m7 11 2-2-2-2" }],
  ["path", { d: "M11 13h4" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }]
];

// node_modules/lucide/dist/esm/icons/square-x.js
var SquareX = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "m9 9 6 6" }]
];

// node_modules/lucide/dist/esm/icons/square-user.js
var SquareUser = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" }]
];

// node_modules/lucide/dist/esm/icons/square.js
var Square = [["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }]];

// node_modules/lucide/dist/esm/icons/squares-exclude.js
var SquaresExclude = [
  [
    "path",
    {
      d: "M16 12v2a2 2 0 0 1-2 2H9a1 1 0 0 0-1 1v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2h0"
    }
  ],
  [
    "path",
    {
      d: "M4 16a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3a1 1 0 0 1-1 1h-5a2 2 0 0 0-2 2v2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/squares-intersect.js
var SquaresIntersect = [
  ["path", { d: "M10 22a2 2 0 0 1-2-2" }],
  ["path", { d: "M14 2a2 2 0 0 1 2 2" }],
  ["path", { d: "M16 22h-2" }],
  ["path", { d: "M2 10V8" }],
  ["path", { d: "M2 4a2 2 0 0 1 2-2" }],
  ["path", { d: "M20 8a2 2 0 0 1 2 2" }],
  ["path", { d: "M22 14v2" }],
  ["path", { d: "M22 20a2 2 0 0 1-2 2" }],
  ["path", { d: "M4 16a2 2 0 0 1-2-2" }],
  ["path", { d: "M8 10a2 2 0 0 1 2-2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z" }],
  ["path", { d: "M8 2h2" }]
];

// node_modules/lucide/dist/esm/icons/squares-subtract.js
var SquaresSubtract = [
  ["path", { d: "M10 22a2 2 0 0 1-2-2" }],
  ["path", { d: "M16 22h-2" }],
  [
    "path",
    {
      d: "M16 4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-5a2 2 0 0 1 2-2h5a1 1 0 0 0 1-1z"
    }
  ],
  ["path", { d: "M20 8a2 2 0 0 1 2 2" }],
  ["path", { d: "M22 14v2" }],
  ["path", { d: "M22 20a2 2 0 0 1-2 2" }]
];

// node_modules/lucide/dist/esm/icons/squares-unite.js
var SquaresUnite = [
  [
    "path",
    {
      d: "M4 16a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3a1 1 0 0 0 1 1h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-3a1 1 0 0 0-1-1z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/squircle.js
var Squircle = [
  ["path", { d: "M12 3c7.2 0 9 1.8 9 9s-1.8 9-9 9-9-1.8-9-9 1.8-9 9-9" }]
];

// node_modules/lucide/dist/esm/icons/squirrel.js
var Squirrel = [
  ["path", { d: "M15.236 22a3 3 0 0 0-2.2-5" }],
  ["path", { d: "M16 20a3 3 0 0 1 3-3h1a2 2 0 0 0 2-2v-2a4 4 0 0 0-4-4V4" }],
  ["path", { d: "M18 13h.01" }],
  [
    "path",
    {
      d: "M18 6a4 4 0 0 0-4 4 7 7 0 0 0-7 7c0-5 4-5 4-10.5a4.5 4.5 0 1 0-9 0 2.5 2.5 0 0 0 5 0C7 10 3 11 3 17c0 2.8 2.2 5 5 5h10"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/stamp.js
var Stamp = [
  ["path", { d: "M5 22h14" }],
  [
    "path",
    {
      d: "M19.27 13.73A2.5 2.5 0 0 0 17.5 13h-11A2.5 2.5 0 0 0 4 15.5V17a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1.5c0-.66-.26-1.3-.73-1.77Z"
    }
  ],
  ["path", { d: "M14 13V8.5C14 7 15 7 15 5a3 3 0 0 0-3-3c-1.66 0-3 1-3 3s1 2 1 3.5V13" }]
];

// node_modules/lucide/dist/esm/icons/star-half.js
var StarHalf = [
  [
    "path",
    {
      d: "M12 18.338a2.1 2.1 0 0 0-.987.244L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.12 2.12 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.12 2.12 0 0 0 1.597-1.16l2.309-4.679A.53.53 0 0 1 12 2"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/star-off.js
var StarOff = [
  ["path", { d: "M8.34 8.34 2 9.27l5 4.87L5.82 21 12 17.77 18.18 21l-.59-3.43" }],
  ["path", { d: "M18.42 12.76 22 9.27l-6.91-1L12 2l-1.44 2.91" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/star.js
var Star = [
  [
    "path",
    {
      d: "M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/step-back.js
var StepBack = [
  ["line", { x1: "18", x2: "18", y1: "20", y2: "4" }],
  ["polygon", { points: "14,20 4,12 14,4" }]
];

// node_modules/lucide/dist/esm/icons/step-forward.js
var StepForward = [
  ["line", { x1: "6", x2: "6", y1: "4", y2: "20" }],
  ["polygon", { points: "10,4 20,12 10,20" }]
];

// node_modules/lucide/dist/esm/icons/sticker.js
var Sticker = [
  ["path", { d: "M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z" }],
  ["path", { d: "M14 3v4a2 2 0 0 0 2 2h4" }],
  ["path", { d: "M8 13h.01" }],
  ["path", { d: "M16 13h.01" }],
  ["path", { d: "M10 16s.8 1 2 1c1.3 0 2-1 2-1" }]
];

// node_modules/lucide/dist/esm/icons/stethoscope.js
var Stethoscope = [
  ["path", { d: "M11 2v2" }],
  ["path", { d: "M5 2v2" }],
  ["path", { d: "M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1" }],
  ["path", { d: "M8 15a6 6 0 0 0 12 0v-3" }],
  ["circle", { cx: "20", cy: "10", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/sticky-note.js
var StickyNote = [
  ["path", { d: "M16 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8Z" }],
  ["path", { d: "M15 3v4a2 2 0 0 0 2 2h4" }]
];

// node_modules/lucide/dist/esm/icons/store.js
var Store = [
  ["path", { d: "m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7" }],
  ["path", { d: "M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" }],
  ["path", { d: "M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4" }],
  ["path", { d: "M2 7h20" }],
  [
    "path",
    {
      d: "M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/stretch-horizontal.js
var StretchHorizontal = [
  ["rect", { width: "20", height: "6", x: "2", y: "4", rx: "2" }],
  ["rect", { width: "20", height: "6", x: "2", y: "14", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/stretch-vertical.js
var StretchVertical = [
  ["rect", { width: "6", height: "20", x: "4", y: "2", rx: "2" }],
  ["rect", { width: "6", height: "20", x: "14", y: "2", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/strikethrough.js
var Strikethrough = [
  ["path", { d: "M16 4H9a3 3 0 0 0-2.83 4" }],
  ["path", { d: "M14 12a4 4 0 0 1 0 8H6" }],
  ["line", { x1: "4", x2: "20", y1: "12", y2: "12" }]
];

// node_modules/lucide/dist/esm/icons/subscript.js
var Subscript = [
  ["path", { d: "m4 5 8 8" }],
  ["path", { d: "m12 5-8 8" }],
  [
    "path",
    {
      d: "M20 19h-4c0-1.5.44-2 1.5-2.5S20 15.33 20 14c0-.47-.17-.93-.48-1.29a2.11 2.11 0 0 0-2.62-.44c-.42.24-.74.62-.9 1.07"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/sun-dim.js
var SunDim = [
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["path", { d: "M12 4h.01" }],
  ["path", { d: "M20 12h.01" }],
  ["path", { d: "M12 20h.01" }],
  ["path", { d: "M4 12h.01" }],
  ["path", { d: "M17.657 6.343h.01" }],
  ["path", { d: "M17.657 17.657h.01" }],
  ["path", { d: "M6.343 17.657h.01" }],
  ["path", { d: "M6.343 6.343h.01" }]
];

// node_modules/lucide/dist/esm/icons/sun-medium.js
var SunMedium = [
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["path", { d: "M12 3v1" }],
  ["path", { d: "M12 20v1" }],
  ["path", { d: "M3 12h1" }],
  ["path", { d: "M20 12h1" }],
  ["path", { d: "m18.364 5.636-.707.707" }],
  ["path", { d: "m6.343 17.657-.707.707" }],
  ["path", { d: "m5.636 5.636.707.707" }],
  ["path", { d: "m17.657 17.657.707.707" }]
];

// node_modules/lucide/dist/esm/icons/sun-moon.js
var SunMoon = [
  ["path", { d: "M12 8a2.83 2.83 0 0 0 4 4 4 4 0 1 1-4-4" }],
  ["path", { d: "M12 2v2" }],
  ["path", { d: "M12 20v2" }],
  ["path", { d: "m4.9 4.9 1.4 1.4" }],
  ["path", { d: "m17.7 17.7 1.4 1.4" }],
  ["path", { d: "M2 12h2" }],
  ["path", { d: "M20 12h2" }],
  ["path", { d: "m6.3 17.7-1.4 1.4" }],
  ["path", { d: "m19.1 4.9-1.4 1.4" }]
];

// node_modules/lucide/dist/esm/icons/sun.js
var Sun = [
  ["circle", { cx: "12", cy: "12", r: "4" }],
  ["path", { d: "M12 2v2" }],
  ["path", { d: "M12 20v2" }],
  ["path", { d: "m4.93 4.93 1.41 1.41" }],
  ["path", { d: "m17.66 17.66 1.41 1.41" }],
  ["path", { d: "M2 12h2" }],
  ["path", { d: "M20 12h2" }],
  ["path", { d: "m6.34 17.66-1.41 1.41" }],
  ["path", { d: "m19.07 4.93-1.41 1.41" }]
];

// node_modules/lucide/dist/esm/icons/sunrise.js
var Sunrise = [
  ["path", { d: "M12 2v8" }],
  ["path", { d: "m4.93 10.93 1.41 1.41" }],
  ["path", { d: "M2 18h2" }],
  ["path", { d: "M20 18h2" }],
  ["path", { d: "m19.07 10.93-1.41 1.41" }],
  ["path", { d: "M22 22H2" }],
  ["path", { d: "m8 6 4-4 4 4" }],
  ["path", { d: "M16 18a4 4 0 0 0-8 0" }]
];

// node_modules/lucide/dist/esm/icons/sun-snow.js
var SunSnow = [
  ["path", { d: "M10 21v-1" }],
  ["path", { d: "M10 4V3" }],
  ["path", { d: "M10 9a3 3 0 0 0 0 6" }],
  ["path", { d: "m14 20 1.25-2.5L18 18" }],
  ["path", { d: "m14 4 1.25 2.5L18 6" }],
  ["path", { d: "m17 21-3-6 1.5-3H22" }],
  ["path", { d: "m17 3-3 6 1.5 3" }],
  ["path", { d: "M2 12h1" }],
  ["path", { d: "m20 10-1.5 2 1.5 2" }],
  ["path", { d: "m3.64 18.36.7-.7" }],
  ["path", { d: "m4.34 6.34-.7-.7" }]
];

// node_modules/lucide/dist/esm/icons/sunset.js
var Sunset = [
  ["path", { d: "M12 10V2" }],
  ["path", { d: "m4.93 10.93 1.41 1.41" }],
  ["path", { d: "M2 18h2" }],
  ["path", { d: "M20 18h2" }],
  ["path", { d: "m19.07 10.93-1.41 1.41" }],
  ["path", { d: "M22 22H2" }],
  ["path", { d: "m16 6-4 4-4-4" }],
  ["path", { d: "M16 18a4 4 0 0 0-8 0" }]
];

// node_modules/lucide/dist/esm/icons/superscript.js
var Superscript = [
  ["path", { d: "m4 19 8-8" }],
  ["path", { d: "m12 19-8-8" }],
  [
    "path",
    {
      d: "M20 12h-4c0-1.5.442-2 1.5-2.5S20 8.334 20 7.002c0-.472-.17-.93-.484-1.29a2.105 2.105 0 0 0-2.617-.436c-.42.239-.738.614-.899 1.06"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/swatch-book.js
var SwatchBook = [
  ["path", { d: "M11 17a4 4 0 0 1-8 0V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2Z" }],
  ["path", { d: "M16.7 13H19a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H7" }],
  ["path", { d: "M 7 17h.01" }],
  [
    "path",
    { d: "m11 8 2.3-2.3a2.4 2.4 0 0 1 3.404.004L18.6 7.6a2.4 2.4 0 0 1 .026 3.434L9.9 19.8" }
  ]
];

// node_modules/lucide/dist/esm/icons/switch-camera.js
var SwitchCamera = [
  ["path", { d: "M11 19H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5" }],
  ["path", { d: "M13 5h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-5" }],
  ["circle", { cx: "12", cy: "12", r: "3" }],
  ["path", { d: "m18 22-3-3 3-3" }],
  ["path", { d: "m6 2 3 3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/sword.js
var Sword = [
  ["polyline", { points: "14.5 17.5 3 6 3 3 6 3 17.5 14.5" }],
  ["line", { x1: "13", x2: "19", y1: "19", y2: "13" }],
  ["line", { x1: "16", x2: "20", y1: "16", y2: "20" }],
  ["line", { x1: "19", x2: "21", y1: "21", y2: "19" }]
];

// node_modules/lucide/dist/esm/icons/swiss-franc.js
var SwissFranc = [
  ["path", { d: "M10 21V3h8" }],
  ["path", { d: "M6 16h9" }],
  ["path", { d: "M10 9.5h7" }]
];

// node_modules/lucide/dist/esm/icons/swords.js
var Swords = [
  ["polyline", { points: "14.5 17.5 3 6 3 3 6 3 17.5 14.5" }],
  ["line", { x1: "13", x2: "19", y1: "19", y2: "13" }],
  ["line", { x1: "16", x2: "20", y1: "16", y2: "20" }],
  ["line", { x1: "19", x2: "21", y1: "21", y2: "19" }],
  ["polyline", { points: "14.5 6.5 18 3 21 3 21 6 17.5 9.5" }],
  ["line", { x1: "5", x2: "9", y1: "14", y2: "18" }],
  ["line", { x1: "7", x2: "4", y1: "17", y2: "20" }],
  ["line", { x1: "3", x2: "5", y1: "19", y2: "21" }]
];

// node_modules/lucide/dist/esm/icons/syringe.js
var Syringe = [
  ["path", { d: "m18 2 4 4" }],
  ["path", { d: "m17 7 3-3" }],
  ["path", { d: "M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5" }],
  ["path", { d: "m9 11 4 4" }],
  ["path", { d: "m5 19-3 3" }],
  ["path", { d: "m14 4 6 6" }]
];

// node_modules/lucide/dist/esm/icons/table-2.js
var Table2 = [
  [
    "path",
    {
      d: "M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/table-cells-merge.js
var TableCellsMerge = [
  ["path", { d: "M12 21v-6" }],
  ["path", { d: "M12 9V3" }],
  ["path", { d: "M3 15h18" }],
  ["path", { d: "M3 9h18" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/table-cells-split.js
var TableCellsSplit = [
  ["path", { d: "M12 15V9" }],
  ["path", { d: "M3 15h18" }],
  ["path", { d: "M3 9h18" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/table-columns-split.js
var TableColumnsSplit = [
  ["path", { d: "M14 14v2" }],
  ["path", { d: "M14 20v2" }],
  ["path", { d: "M14 2v2" }],
  ["path", { d: "M14 8v2" }],
  ["path", { d: "M2 15h8" }],
  ["path", { d: "M2 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H2" }],
  ["path", { d: "M2 9h8" }],
  ["path", { d: "M22 15h-4" }],
  ["path", { d: "M22 3h-2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2" }],
  ["path", { d: "M22 9h-4" }],
  ["path", { d: "M5 3v18" }]
];

// node_modules/lucide/dist/esm/icons/table-of-contents.js
var TableOfContents = [
  ["path", { d: "M16 12H3" }],
  ["path", { d: "M16 18H3" }],
  ["path", { d: "M16 6H3" }],
  ["path", { d: "M21 12h.01" }],
  ["path", { d: "M21 18h.01" }],
  ["path", { d: "M21 6h.01" }]
];

// node_modules/lucide/dist/esm/icons/table-properties.js
var TableProperties = [
  ["path", { d: "M15 3v18" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M21 9H3" }],
  ["path", { d: "M21 15H3" }]
];

// node_modules/lucide/dist/esm/icons/table-rows-split.js
var TableRowsSplit = [
  ["path", { d: "M14 10h2" }],
  ["path", { d: "M15 22v-8" }],
  ["path", { d: "M15 2v4" }],
  ["path", { d: "M2 10h2" }],
  ["path", { d: "M20 10h2" }],
  ["path", { d: "M3 19h18" }],
  ["path", { d: "M3 22v-6a2 2 135 0 1 2-2h14a2 2 45 0 1 2 2v6" }],
  ["path", { d: "M3 2v2a2 2 45 0 0 2 2h14a2 2 135 0 0 2-2V2" }],
  ["path", { d: "M8 10h2" }],
  ["path", { d: "M9 22v-8" }],
  ["path", { d: "M9 2v4" }]
];

// node_modules/lucide/dist/esm/icons/table.js
var Table = [
  ["path", { d: "M12 3v18" }],
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9h18" }],
  ["path", { d: "M3 15h18" }]
];

// node_modules/lucide/dist/esm/icons/tablet-smartphone.js
var TabletSmartphone = [
  ["rect", { width: "10", height: "14", x: "3", y: "8", rx: "2" }],
  ["path", { d: "M5 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2h-2.4" }],
  ["path", { d: "M8 18h.01" }]
];

// node_modules/lucide/dist/esm/icons/tablet.js
var Tablet = [
  ["rect", { width: "16", height: "20", x: "4", y: "2", rx: "2", ry: "2" }],
  ["line", { x1: "12", x2: "12.01", y1: "18", y2: "18" }]
];

// node_modules/lucide/dist/esm/icons/tag.js
var Tag = [
  [
    "path",
    {
      d: "M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"
    }
  ],
  ["circle", { cx: "7.5", cy: "7.5", r: ".5", fill: "currentColor" }]
];

// node_modules/lucide/dist/esm/icons/tags.js
var Tags = [
  ["path", { d: "m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19" }],
  [
    "path",
    {
      d: "M9.586 5.586A2 2 0 0 0 8.172 5H3a1 1 0 0 0-1 1v5.172a2 2 0 0 0 .586 1.414L8.29 18.29a2.426 2.426 0 0 0 3.42 0l3.58-3.58a2.426 2.426 0 0 0 0-3.42z"
    }
  ],
  ["circle", { cx: "6.5", cy: "9.5", r: ".5", fill: "currentColor" }]
];

// node_modules/lucide/dist/esm/icons/tablets.js
var Tablets = [
  ["circle", { cx: "7", cy: "7", r: "5" }],
  ["circle", { cx: "17", cy: "17", r: "5" }],
  ["path", { d: "M12 17h10" }],
  ["path", { d: "m3.46 10.54 7.08-7.08" }]
];

// node_modules/lucide/dist/esm/icons/tally-1.js
var Tally1 = [["path", { d: "M4 4v16" }]];

// node_modules/lucide/dist/esm/icons/tally-2.js
var Tally2 = [
  ["path", { d: "M4 4v16" }],
  ["path", { d: "M9 4v16" }]
];

// node_modules/lucide/dist/esm/icons/tally-3.js
var Tally3 = [
  ["path", { d: "M4 4v16" }],
  ["path", { d: "M9 4v16" }],
  ["path", { d: "M14 4v16" }]
];

// node_modules/lucide/dist/esm/icons/tally-4.js
var Tally4 = [
  ["path", { d: "M4 4v16" }],
  ["path", { d: "M9 4v16" }],
  ["path", { d: "M14 4v16" }],
  ["path", { d: "M19 4v16" }]
];

// node_modules/lucide/dist/esm/icons/tally-5.js
var Tally5 = [
  ["path", { d: "M4 4v16" }],
  ["path", { d: "M9 4v16" }],
  ["path", { d: "M14 4v16" }],
  ["path", { d: "M19 4v16" }],
  ["path", { d: "M22 6 2 18" }]
];

// node_modules/lucide/dist/esm/icons/target.js
var Target = [
  ["circle", { cx: "12", cy: "12", r: "10" }],
  ["circle", { cx: "12", cy: "12", r: "6" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/tangent.js
var Tangent = [
  ["circle", { cx: "17", cy: "4", r: "2" }],
  ["path", { d: "M15.59 5.41 5.41 15.59" }],
  ["circle", { cx: "4", cy: "17", r: "2" }],
  ["path", { d: "M12 22s-4-9-1.5-11.5S22 12 22 12" }]
];

// node_modules/lucide/dist/esm/icons/tent-tree.js
var TentTree = [
  ["circle", { cx: "4", cy: "4", r: "2" }],
  ["path", { d: "m14 5 3-3 3 3" }],
  ["path", { d: "m14 10 3-3 3 3" }],
  ["path", { d: "M17 14V2" }],
  ["path", { d: "M17 14H7l-5 8h20Z" }],
  ["path", { d: "M8 14v8" }],
  ["path", { d: "m9 14 5 8" }]
];

// node_modules/lucide/dist/esm/icons/telescope.js
var Telescope = [
  [
    "path",
    {
      d: "m10.065 12.493-6.18 1.318a.934.934 0 0 1-1.108-.702l-.537-2.15a1.07 1.07 0 0 1 .691-1.265l13.504-4.44"
    }
  ],
  ["path", { d: "m13.56 11.747 4.332-.924" }],
  ["path", { d: "m16 21-3.105-6.21" }],
  [
    "path",
    {
      d: "M16.485 5.94a2 2 0 0 1 1.455-2.425l1.09-.272a1 1 0 0 1 1.212.727l1.515 6.06a1 1 0 0 1-.727 1.213l-1.09.272a2 2 0 0 1-2.425-1.455z"
    }
  ],
  ["path", { d: "m6.158 8.633 1.114 4.456" }],
  ["path", { d: "m8 21 3.105-6.21" }],
  ["circle", { cx: "12", cy: "13", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/tent.js
var Tent = [
  ["path", { d: "M3.5 21 14 3" }],
  ["path", { d: "M20.5 21 10 3" }],
  ["path", { d: "M15.5 21 12 15l-3.5 6" }],
  ["path", { d: "M2 21h20" }]
];

// node_modules/lucide/dist/esm/icons/terminal.js
var Terminal = [
  ["polyline", { points: "4 17 10 11 4 5" }],
  ["line", { x1: "12", x2: "20", y1: "19", y2: "19" }]
];

// node_modules/lucide/dist/esm/icons/test-tube-diagonal.js
var TestTubeDiagonal = [
  ["path", { d: "M21 7 6.82 21.18a2.83 2.83 0 0 1-3.99-.01a2.83 2.83 0 0 1 0-4L17 3" }],
  ["path", { d: "m16 2 6 6" }],
  ["path", { d: "M12 16H4" }]
];

// node_modules/lucide/dist/esm/icons/test-tube.js
var TestTube = [
  ["path", { d: "M14.5 2v17.5c0 1.4-1.1 2.5-2.5 2.5c-1.4 0-2.5-1.1-2.5-2.5V2" }],
  ["path", { d: "M8.5 2h7" }],
  ["path", { d: "M14.5 16h-5" }]
];

// node_modules/lucide/dist/esm/icons/test-tubes.js
var TestTubes = [
  ["path", { d: "M9 2v17.5A2.5 2.5 0 0 1 6.5 22A2.5 2.5 0 0 1 4 19.5V2" }],
  ["path", { d: "M20 2v17.5a2.5 2.5 0 0 1-2.5 2.5a2.5 2.5 0 0 1-2.5-2.5V2" }],
  ["path", { d: "M3 2h7" }],
  ["path", { d: "M14 2h7" }],
  ["path", { d: "M9 16H4" }],
  ["path", { d: "M20 16h-5" }]
];

// node_modules/lucide/dist/esm/icons/text-cursor-input.js
var TextCursorInput = [
  ["path", { d: "M12 20h-1a2 2 0 0 1-2-2 2 2 0 0 1-2 2H6" }],
  ["path", { d: "M13 8h7a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-7" }],
  ["path", { d: "M5 16H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h1" }],
  ["path", { d: "M6 4h1a2 2 0 0 1 2 2 2 2 0 0 1 2-2h1" }],
  ["path", { d: "M9 6v12" }]
];

// node_modules/lucide/dist/esm/icons/text-cursor.js
var TextCursor = [
  ["path", { d: "M17 22h-1a4 4 0 0 1-4-4V6a4 4 0 0 1 4-4h1" }],
  ["path", { d: "M7 22h1a4 4 0 0 0 4-4v-1" }],
  ["path", { d: "M7 2h1a4 4 0 0 1 4 4v1" }]
];

// node_modules/lucide/dist/esm/icons/text-quote.js
var TextQuote = [
  ["path", { d: "M17 6H3" }],
  ["path", { d: "M21 12H8" }],
  ["path", { d: "M21 18H8" }],
  ["path", { d: "M3 12v6" }]
];

// node_modules/lucide/dist/esm/icons/text-search.js
var TextSearch = [
  ["path", { d: "M21 6H3" }],
  ["path", { d: "M10 12H3" }],
  ["path", { d: "M10 18H3" }],
  ["circle", { cx: "17", cy: "15", r: "3" }],
  ["path", { d: "m21 19-1.9-1.9" }]
];

// node_modules/lucide/dist/esm/icons/text-select.js
var TextSelect = [
  ["path", { d: "M14 21h1" }],
  ["path", { d: "M14 3h1" }],
  ["path", { d: "M19 3a2 2 0 0 1 2 2" }],
  ["path", { d: "M21 14v1" }],
  ["path", { d: "M21 19a2 2 0 0 1-2 2" }],
  ["path", { d: "M21 9v1" }],
  ["path", { d: "M3 14v1" }],
  ["path", { d: "M3 9v1" }],
  ["path", { d: "M5 21a2 2 0 0 1-2-2" }],
  ["path", { d: "M5 3a2 2 0 0 0-2 2" }],
  ["path", { d: "M7 12h10" }],
  ["path", { d: "M7 16h6" }],
  ["path", { d: "M7 8h8" }],
  ["path", { d: "M9 21h1" }],
  ["path", { d: "M9 3h1" }]
];

// node_modules/lucide/dist/esm/icons/theater.js
var Theater = [
  ["path", { d: "M2 10s3-3 3-8" }],
  ["path", { d: "M22 10s-3-3-3-8" }],
  ["path", { d: "M10 2c0 4.4-3.6 8-8 8" }],
  ["path", { d: "M14 2c0 4.4 3.6 8 8 8" }],
  ["path", { d: "M2 10s2 2 2 5" }],
  ["path", { d: "M22 10s-2 2-2 5" }],
  ["path", { d: "M8 15h8" }],
  ["path", { d: "M2 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1" }],
  ["path", { d: "M14 22v-1a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1" }]
];

// node_modules/lucide/dist/esm/icons/text.js
var Text = [
  ["path", { d: "M15 18H3" }],
  ["path", { d: "M17 6H3" }],
  ["path", { d: "M21 12H3" }]
];

// node_modules/lucide/dist/esm/icons/thermometer-sun.js
var ThermometerSun = [
  ["path", { d: "M12 9a4 4 0 0 0-2 7.5" }],
  ["path", { d: "M12 3v2" }],
  ["path", { d: "m6.6 18.4-1.4 1.4" }],
  ["path", { d: "M20 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z" }],
  ["path", { d: "M4 13H2" }],
  ["path", { d: "M6.34 7.34 4.93 5.93" }]
];

// node_modules/lucide/dist/esm/icons/thermometer-snowflake.js
var ThermometerSnowflake = [
  ["path", { d: "m10 20-1.25-2.5L6 18" }],
  ["path", { d: "M10 4 8.75 6.5 6 6" }],
  ["path", { d: "M10.585 15H10" }],
  ["path", { d: "M2 12h6.5L10 9" }],
  ["path", { d: "M20 14.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0z" }],
  ["path", { d: "m4 10 1.5 2L4 14" }],
  ["path", { d: "m7 21 3-6-1.5-3" }],
  ["path", { d: "m7 3 3 6h2" }]
];

// node_modules/lucide/dist/esm/icons/thermometer.js
var Thermometer = [["path", { d: "M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z" }]];

// node_modules/lucide/dist/esm/icons/thumbs-down.js
var ThumbsDown = [
  ["path", { d: "M17 14V2" }],
  [
    "path",
    {
      d: "M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22a3.13 3.13 0 0 1-3-3.88Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/thumbs-up.js
var ThumbsUp = [
  ["path", { d: "M7 10v12" }],
  [
    "path",
    {
      d: "M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/ticket-check.js
var TicketCheck = [
  [
    "path",
    {
      d: "M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "m9 12 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/ticket-minus.js
var TicketMinus = [
  [
    "path",
    {
      d: "M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "M9 12h6" }]
];

// node_modules/lucide/dist/esm/icons/ticket-percent.js
var TicketPercent = [
  [
    "path",
    {
      d: "M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "M9 9h.01" }],
  ["path", { d: "m15 9-6 6" }],
  ["path", { d: "M15 15h.01" }]
];

// node_modules/lucide/dist/esm/icons/ticket-slash.js
var TicketSlash = [
  [
    "path",
    {
      d: "M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "m9.5 14.5 5-5" }]
];

// node_modules/lucide/dist/esm/icons/ticket-plus.js
var TicketPlus = [
  [
    "path",
    {
      d: "M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "M9 12h6" }],
  ["path", { d: "M12 9v6" }]
];

// node_modules/lucide/dist/esm/icons/ticket-x.js
var TicketX = [
  [
    "path",
    {
      d: "M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "m9.5 14.5 5-5" }],
  ["path", { d: "m9.5 9.5 5 5" }]
];

// node_modules/lucide/dist/esm/icons/ticket.js
var Ticket = [
  [
    "path",
    {
      d: "M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"
    }
  ],
  ["path", { d: "M13 5v2" }],
  ["path", { d: "M13 17v2" }],
  ["path", { d: "M13 11v2" }]
];

// node_modules/lucide/dist/esm/icons/tickets-plane.js
var TicketsPlane = [
  ["path", { d: "M10.5 17h1.227a2 2 0 0 0 1.345-.52L18 12" }],
  ["path", { d: "m12 13.5 3.75.5" }],
  ["path", { d: "m4.5 8 10.58-5.06a1 1 0 0 1 1.342.488L18.5 8" }],
  ["path", { d: "M6 10V8" }],
  ["path", { d: "M6 14v1" }],
  ["path", { d: "M6 19v2" }],
  ["rect", { x: "2", y: "8", width: "20", height: "13", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/tickets.js
var Tickets = [
  ["path", { d: "m4.5 8 10.58-5.06a1 1 0 0 1 1.342.488L18.5 8" }],
  ["path", { d: "M6 10V8" }],
  ["path", { d: "M6 14v1" }],
  ["path", { d: "M6 19v2" }],
  ["rect", { x: "2", y: "8", width: "20", height: "13", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/timer-reset.js
var TimerReset = [
  ["path", { d: "M10 2h4" }],
  ["path", { d: "M12 14v-4" }],
  ["path", { d: "M4 13a8 8 0 0 1 8-7 8 8 0 1 1-5.3 14L4 17.6" }],
  ["path", { d: "M9 17H4v5" }]
];

// node_modules/lucide/dist/esm/icons/timer.js
var Timer = [
  ["line", { x1: "10", x2: "14", y1: "2", y2: "2" }],
  ["line", { x1: "12", x2: "15", y1: "14", y2: "11" }],
  ["circle", { cx: "12", cy: "14", r: "8" }]
];

// node_modules/lucide/dist/esm/icons/timer-off.js
var TimerOff = [
  ["path", { d: "M10 2h4" }],
  ["path", { d: "M4.6 11a8 8 0 0 0 1.7 8.7 8 8 0 0 0 8.7 1.7" }],
  ["path", { d: "M7.4 7.4a8 8 0 0 1 10.3 1 8 8 0 0 1 .9 10.2" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M12 12v-2" }]
];

// node_modules/lucide/dist/esm/icons/toggle-left.js
var ToggleLeft = [
  ["circle", { cx: "9", cy: "12", r: "3" }],
  ["rect", { width: "20", height: "14", x: "2", y: "5", rx: "7" }]
];

// node_modules/lucide/dist/esm/icons/toggle-right.js
var ToggleRight = [
  ["circle", { cx: "15", cy: "12", r: "3" }],
  ["rect", { width: "20", height: "14", x: "2", y: "5", rx: "7" }]
];

// node_modules/lucide/dist/esm/icons/toilet.js
var Toilet = [
  [
    "path",
    {
      d: "M7 12h13a1 1 0 0 1 1 1 5 5 0 0 1-5 5h-.598a.5.5 0 0 0-.424.765l1.544 2.47a.5.5 0 0 1-.424.765H5.402a.5.5 0 0 1-.424-.765L7 18"
    }
  ],
  ["path", { d: "M8 18a5 5 0 0 1-5-5V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8" }]
];

// node_modules/lucide/dist/esm/icons/tornado.js
var Tornado = [
  ["path", { d: "M21 4H3" }],
  ["path", { d: "M18 8H6" }],
  ["path", { d: "M19 12H9" }],
  ["path", { d: "M16 16h-6" }],
  ["path", { d: "M11 20H9" }]
];

// node_modules/lucide/dist/esm/icons/torus.js
var Torus = [
  ["ellipse", { cx: "12", cy: "11", rx: "3", ry: "2" }],
  ["ellipse", { cx: "12", cy: "12.5", rx: "10", ry: "8.5" }]
];

// node_modules/lucide/dist/esm/icons/touchpad-off.js
var TouchpadOff = [
  ["path", { d: "M12 20v-6" }],
  ["path", { d: "M19.656 14H22" }],
  ["path", { d: "M2 14h12" }],
  ["path", { d: "m2 2 20 20" }],
  ["path", { d: "M20 20H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2" }],
  ["path", { d: "M9.656 4H20a2 2 0 0 1 2 2v10.344" }]
];

// node_modules/lucide/dist/esm/icons/touchpad.js
var Touchpad = [
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["path", { d: "M2 14h20" }],
  ["path", { d: "M12 20v-6" }]
];

// node_modules/lucide/dist/esm/icons/tower-control.js
var TowerControl = [
  ["path", { d: "M18.2 12.27 20 6H4l1.8 6.27a1 1 0 0 0 .95.73h10.5a1 1 0 0 0 .96-.73Z" }],
  ["path", { d: "M8 13v9" }],
  ["path", { d: "M16 22v-9" }],
  ["path", { d: "m9 6 1 7" }],
  ["path", { d: "m15 6-1 7" }],
  ["path", { d: "M12 6V2" }],
  ["path", { d: "M13 2h-2" }]
];

// node_modules/lucide/dist/esm/icons/toy-brick.js
var ToyBrick = [
  ["rect", { width: "18", height: "12", x: "3", y: "8", rx: "1" }],
  ["path", { d: "M10 8V5c0-.6-.4-1-1-1H6a1 1 0 0 0-1 1v3" }],
  ["path", { d: "M19 8V5c0-.6-.4-1-1-1h-3a1 1 0 0 0-1 1v3" }]
];

// node_modules/lucide/dist/esm/icons/tractor.js
var Tractor = [
  ["path", { d: "m10 11 11 .9a1 1 0 0 1 .8 1.1l-.665 4.158a1 1 0 0 1-.988.842H20" }],
  ["path", { d: "M16 18h-5" }],
  ["path", { d: "M18 5a1 1 0 0 0-1 1v5.573" }],
  ["path", { d: "M3 4h8.129a1 1 0 0 1 .99.863L13 11.246" }],
  ["path", { d: "M4 11V4" }],
  ["path", { d: "M7 15h.01" }],
  ["path", { d: "M8 10.1V4" }],
  ["circle", { cx: "18", cy: "18", r: "2" }],
  ["circle", { cx: "7", cy: "15", r: "5" }]
];

// node_modules/lucide/dist/esm/icons/traffic-cone.js
var TrafficCone = [
  ["path", { d: "M16.05 10.966a5 2.5 0 0 1-8.1 0" }],
  [
    "path",
    {
      d: "m16.923 14.049 4.48 2.04a1 1 0 0 1 .001 1.831l-8.574 3.9a2 2 0 0 1-1.66 0l-8.574-3.91a1 1 0 0 1 0-1.83l4.484-2.04"
    }
  ],
  ["path", { d: "M16.949 14.14a5 2.5 0 1 1-9.9 0L10.063 3.5a2 2 0 0 1 3.874 0z" }],
  ["path", { d: "M9.194 6.57a5 2.5 0 0 0 5.61 0" }]
];

// node_modules/lucide/dist/esm/icons/train-front-tunnel.js
var TrainFrontTunnel = [
  ["path", { d: "M2 22V12a10 10 0 1 1 20 0v10" }],
  ["path", { d: "M15 6.8v1.4a3 2.8 0 1 1-6 0V6.8" }],
  ["path", { d: "M10 15h.01" }],
  ["path", { d: "M14 15h.01" }],
  ["path", { d: "M10 19a4 4 0 0 1-4-4v-3a6 6 0 1 1 12 0v3a4 4 0 0 1-4 4Z" }],
  ["path", { d: "m9 19-2 3" }],
  ["path", { d: "m15 19 2 3" }]
];

// node_modules/lucide/dist/esm/icons/train-front.js
var TrainFront = [
  ["path", { d: "M8 3.1V7a4 4 0 0 0 8 0V3.1" }],
  ["path", { d: "m9 15-1-1" }],
  ["path", { d: "m15 15 1-1" }],
  ["path", { d: "M9 19c-2.8 0-5-2.2-5-5v-4a8 8 0 0 1 16 0v4c0 2.8-2.2 5-5 5Z" }],
  ["path", { d: "m8 19-2 3" }],
  ["path", { d: "m16 19 2 3" }]
];

// node_modules/lucide/dist/esm/icons/train-track.js
var TrainTrack = [
  ["path", { d: "M2 17 17 2" }],
  ["path", { d: "m2 14 8 8" }],
  ["path", { d: "m5 11 8 8" }],
  ["path", { d: "m8 8 8 8" }],
  ["path", { d: "m11 5 8 8" }],
  ["path", { d: "m14 2 8 8" }],
  ["path", { d: "M7 22 22 7" }]
];

// node_modules/lucide/dist/esm/icons/tram-front.js
var TramFront = [
  ["rect", { width: "16", height: "16", x: "4", y: "3", rx: "2" }],
  ["path", { d: "M4 11h16" }],
  ["path", { d: "M12 3v8" }],
  ["path", { d: "m8 19-2 3" }],
  ["path", { d: "m18 22-2-3" }],
  ["path", { d: "M8 15h.01" }],
  ["path", { d: "M16 15h.01" }]
];

// node_modules/lucide/dist/esm/icons/transgender.js
var Transgender = [
  ["path", { d: "M12 16v6" }],
  ["path", { d: "M14 20h-4" }],
  ["path", { d: "M18 2h4v4" }],
  ["path", { d: "m2 2 7.17 7.17" }],
  ["path", { d: "M2 5.355V2h3.357" }],
  ["path", { d: "m22 2-7.17 7.17" }],
  ["path", { d: "M8 5 5 8" }],
  ["circle", { cx: "12", cy: "12", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/trash-2.js
var Trash2 = [
  ["path", { d: "M3 6h18" }],
  ["path", { d: "M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" }],
  ["path", { d: "M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" }],
  ["line", { x1: "10", x2: "10", y1: "11", y2: "17" }],
  ["line", { x1: "14", x2: "14", y1: "11", y2: "17" }]
];

// node_modules/lucide/dist/esm/icons/trash.js
var Trash = [
  ["path", { d: "M3 6h18" }],
  ["path", { d: "M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" }],
  ["path", { d: "M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" }]
];

// node_modules/lucide/dist/esm/icons/tree-deciduous.js
var TreeDeciduous = [
  [
    "path",
    {
      d: "M8 19a4 4 0 0 1-2.24-7.32A3.5 3.5 0 0 1 9 6.03V6a3 3 0 1 1 6 0v.04a3.5 3.5 0 0 1 3.24 5.65A4 4 0 0 1 16 19Z"
    }
  ],
  ["path", { d: "M12 19v3" }]
];

// node_modules/lucide/dist/esm/icons/tree-palm.js
var TreePalm = [
  ["path", { d: "M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h2l1-1 1 1h4" }],
  ["path", { d: "M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-3l-1-1-1 1h-3" }],
  [
    "path",
    {
      d: "M5.89 9.71c-2.15 2.15-2.3 5.47-.35 7.43l4.24-4.25.7-.7.71-.71 2.12-2.12c-1.95-1.96-5.27-1.8-7.42.35"
    }
  ],
  ["path", { d: "M11 15.5c.5 2.5-.17 4.5-1 6.5h4c2-5.5-.5-12-1-14" }]
];

// node_modules/lucide/dist/esm/icons/trees.js
var Trees = [
  ["path", { d: "M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z" }],
  ["path", { d: "M7 16v6" }],
  ["path", { d: "M13 19v3" }],
  [
    "path",
    {
      d: "M12 19h8.3a1 1 0 0 0 .7-1.7L18 14h.3a1 1 0 0 0 .7-1.7L16 9h.2a1 1 0 0 0 .8-1.7L13 3l-1.4 1.5"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/tree-pine.js
var TreePine = [
  [
    "path",
    {
      d: "m17 14 3 3.3a1 1 0 0 1-.7 1.7H4.7a1 1 0 0 1-.7-1.7L7 14h-.3a1 1 0 0 1-.7-1.7L9 9h-.2A1 1 0 0 1 8 7.3L12 3l4 4.3a1 1 0 0 1-.8 1.7H15l3 3.3a1 1 0 0 1-.7 1.7H17Z"
    }
  ],
  ["path", { d: "M12 22v-3" }]
];

// node_modules/lucide/dist/esm/icons/trello.js
var Trello = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2", ry: "2" }],
  ["rect", { width: "3", height: "9", x: "7", y: "7" }],
  ["rect", { width: "3", height: "5", x: "14", y: "7" }]
];

// node_modules/lucide/dist/esm/icons/trending-down.js
var TrendingDown = [
  ["polyline", { points: "22 17 13.5 8.5 8.5 13.5 2 7" }],
  ["polyline", { points: "16 17 22 17 22 11" }]
];

// node_modules/lucide/dist/esm/icons/trending-up-down.js
var TrendingUpDown = [
  ["path", { d: "M14.828 14.828 21 21" }],
  ["path", { d: "M21 16v5h-5" }],
  ["path", { d: "m21 3-9 9-4-4-6 6" }],
  ["path", { d: "M21 8V3h-5" }]
];

// node_modules/lucide/dist/esm/icons/trending-up.js
var TrendingUp = [
  ["polyline", { points: "22 7 13.5 15.5 8.5 10.5 2 17" }],
  ["polyline", { points: "16 7 22 7 22 13" }]
];

// node_modules/lucide/dist/esm/icons/triangle-alert.js
var TriangleAlert = [
  ["path", { d: "m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" }],
  ["path", { d: "M12 9v4" }],
  ["path", { d: "M12 17h.01" }]
];

// node_modules/lucide/dist/esm/icons/triangle-dashed.js
var TriangleDashed = [
  ["path", { d: "M10.17 4.193a2 2 0 0 1 3.666.013" }],
  ["path", { d: "M14 21h2" }],
  ["path", { d: "m15.874 7.743 1 1.732" }],
  ["path", { d: "m18.849 12.952 1 1.732" }],
  ["path", { d: "M21.824 18.18a2 2 0 0 1-1.835 2.824" }],
  ["path", { d: "M4.024 21a2 2 0 0 1-1.839-2.839" }],
  ["path", { d: "m5.136 12.952-1 1.732" }],
  ["path", { d: "M8 21h2" }],
  ["path", { d: "m8.102 7.743-1 1.732" }]
];

// node_modules/lucide/dist/esm/icons/triangle-right.js
var TriangleRight = [
  ["path", { d: "M22 18a2 2 0 0 1-2 2H3c-1.1 0-1.3-.6-.4-1.3L20.4 4.3c.9-.7 1.6-.4 1.6.7Z" }]
];

// node_modules/lucide/dist/esm/icons/triangle.js
var Triangle = [
  ["path", { d: "M13.73 4a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" }]
];

// node_modules/lucide/dist/esm/icons/trophy.js
var Trophy = [
  ["path", { d: "M6 9H4.5a2.5 2.5 0 0 1 0-5H6" }],
  ["path", { d: "M18 9h1.5a2.5 2.5 0 0 0 0-5H18" }],
  ["path", { d: "M4 22h16" }],
  ["path", { d: "M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" }],
  ["path", { d: "M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" }],
  ["path", { d: "M18 2H6v7a6 6 0 0 0 12 0V2Z" }]
];

// node_modules/lucide/dist/esm/icons/truck.js
var Truck = [
  ["path", { d: "M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" }],
  ["path", { d: "M15 18H9" }],
  [
    "path",
    { d: "M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" }
  ],
  ["circle", { cx: "17", cy: "18", r: "2" }],
  ["circle", { cx: "7", cy: "18", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/truck-electric.js
var TruckElectric = [
  ["path", { d: "M14 19V7a2 2 0 0 0-2-2H9" }],
  ["path", { d: "M15 19H9" }],
  ["path", { d: "M19 19h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62L18.3 9.38a1 1 0 0 0-.78-.38H14" }],
  ["path", { d: "M2 13v5a1 1 0 0 0 1 1h2" }],
  ["path", { d: "M4 3 2.15 5.15a.495.495 0 0 0 .35.86h2.15a.47.47 0 0 1 .35.86L3 9.02" }],
  ["circle", { cx: "17", cy: "19", r: "2" }],
  ["circle", { cx: "7", cy: "19", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/turtle.js
var Turtle = [
  [
    "path",
    {
      d: "m12 10 2 4v3a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-3a8 8 0 1 0-16 0v3a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-3l2-4h4Z"
    }
  ],
  ["path", { d: "M4.82 7.9 8 10" }],
  ["path", { d: "M15.18 7.9 12 10" }],
  ["path", { d: "M16.93 10H20a2 2 0 0 1 0 4H2" }]
];

// node_modules/lucide/dist/esm/icons/tv-minimal-play.js
var TvMinimalPlay = [
  [
    "path",
    {
      d: "M10 7.75a.75.75 0 0 1 1.142-.638l3.664 2.249a.75.75 0 0 1 0 1.278l-3.664 2.25a.75.75 0 0 1-1.142-.64z"
    }
  ],
  ["path", { d: "M7 21h10" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/tv.js
var Tv = [
  ["rect", { width: "20", height: "15", x: "2", y: "7", rx: "2", ry: "2" }],
  ["polyline", { points: "17 2 12 7 7 2" }]
];

// node_modules/lucide/dist/esm/icons/twitch.js
var Twitch = [["path", { d: "M21 2H3v16h5v4l4-4h5l4-4V2zm-10 9V7m5 4V7" }]];

// node_modules/lucide/dist/esm/icons/tv-minimal.js
var TvMinimal = [
  ["path", { d: "M7 21h10" }],
  ["rect", { width: "20", height: "14", x: "2", y: "3", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/twitter.js
var Twitter = [
  [
    "path",
    {
      d: "M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/type-outline.js
var TypeOutline = [
  [
    "path",
    {
      d: "M14 16.5a.5.5 0 0 0 .5.5h.5a2 2 0 0 1 0 4H9a2 2 0 0 1 0-4h.5a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V8a2 2 0 0 1-4 0V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v3a2 2 0 0 1-4 0v-.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/type.js
var Type = [
  ["polyline", { points: "4 7 4 4 20 4 20 7" }],
  ["line", { x1: "9", x2: "15", y1: "20", y2: "20" }],
  ["line", { x1: "12", x2: "12", y1: "4", y2: "20" }]
];

// node_modules/lucide/dist/esm/icons/umbrella-off.js
var UmbrellaOff = [
  ["path", { d: "M12 2v1" }],
  ["path", { d: "M15.5 21a1.85 1.85 0 0 1-3.5-1v-8H2a10 10 0 0 1 3.428-6.575" }],
  ["path", { d: "M17.5 12H22A10 10 0 0 0 9.004 3.455" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/underline.js
var Underline = [
  ["path", { d: "M6 4v6a6 6 0 0 0 12 0V4" }],
  ["line", { x1: "4", x2: "20", y1: "20", y2: "20" }]
];

// node_modules/lucide/dist/esm/icons/umbrella.js
var Umbrella = [
  ["path", { d: "M22 12a10.06 10.06 1 0 0-20 0Z" }],
  ["path", { d: "M12 12v8a2 2 0 0 0 4 0" }],
  ["path", { d: "M12 2v1" }]
];

// node_modules/lucide/dist/esm/icons/undo-2.js
var Undo2 = [
  ["path", { d: "M9 14 4 9l5-5" }],
  ["path", { d: "M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" }]
];

// node_modules/lucide/dist/esm/icons/undo-dot.js
var UndoDot = [
  ["path", { d: "M21 17a9 9 0 0 0-15-6.7L3 13" }],
  ["path", { d: "M3 7v6h6" }],
  ["circle", { cx: "12", cy: "17", r: "1" }]
];

// node_modules/lucide/dist/esm/icons/undo.js
var Undo = [
  ["path", { d: "M3 7v6h6" }],
  ["path", { d: "M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13" }]
];

// node_modules/lucide/dist/esm/icons/unfold-horizontal.js
var UnfoldHorizontal = [
  ["path", { d: "M16 12h6" }],
  ["path", { d: "M8 12H2" }],
  ["path", { d: "M12 2v2" }],
  ["path", { d: "M12 8v2" }],
  ["path", { d: "M12 14v2" }],
  ["path", { d: "M12 20v2" }],
  ["path", { d: "m19 15 3-3-3-3" }],
  ["path", { d: "m5 9-3 3 3 3" }]
];

// node_modules/lucide/dist/esm/icons/ungroup.js
var Ungroup = [
  ["rect", { width: "8", height: "6", x: "5", y: "4", rx: "1" }],
  ["rect", { width: "8", height: "6", x: "11", y: "14", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/unfold-vertical.js
var UnfoldVertical = [
  ["path", { d: "M12 22v-6" }],
  ["path", { d: "M12 8V2" }],
  ["path", { d: "M4 12H2" }],
  ["path", { d: "M10 12H8" }],
  ["path", { d: "M16 12h-2" }],
  ["path", { d: "M22 12h-2" }],
  ["path", { d: "m15 19-3 3-3-3" }],
  ["path", { d: "m15 5-3-3-3 3" }]
];

// node_modules/lucide/dist/esm/icons/university.js
var University = [
  ["circle", { cx: "12", cy: "10", r: "1" }],
  ["path", { d: "M22 20V8h-4l-6-4-6 4H2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2" }],
  ["path", { d: "M6 17v.01" }],
  ["path", { d: "M6 13v.01" }],
  ["path", { d: "M18 17v.01" }],
  ["path", { d: "M18 13v.01" }],
  ["path", { d: "M14 22v-5a2 2 0 0 0-2-2a2 2 0 0 0-2 2v5" }]
];

// node_modules/lucide/dist/esm/icons/unlink.js
var Unlink = [
  [
    "path",
    {
      d: "m18.84 12.25 1.72-1.71h-.02a5.004 5.004 0 0 0-.12-7.07 5.006 5.006 0 0 0-6.95 0l-1.72 1.71"
    }
  ],
  [
    "path",
    { d: "m5.17 11.75-1.71 1.71a5.004 5.004 0 0 0 .12 7.07 5.006 5.006 0 0 0 6.95 0l1.71-1.71" }
  ],
  ["line", { x1: "8", x2: "8", y1: "2", y2: "5" }],
  ["line", { x1: "2", x2: "5", y1: "8", y2: "8" }],
  ["line", { x1: "16", x2: "16", y1: "19", y2: "22" }],
  ["line", { x1: "19", x2: "22", y1: "16", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/unlink-2.js
var Unlink2 = [["path", { d: "M15 7h2a5 5 0 0 1 0 10h-2m-6 0H7A5 5 0 0 1 7 7h2" }]];

// node_modules/lucide/dist/esm/icons/unplug.js
var Unplug = [
  ["path", { d: "m19 5 3-3" }],
  ["path", { d: "m2 22 3-3" }],
  ["path", { d: "M6.3 20.3a2.4 2.4 0 0 0 3.4 0L12 18l-6-6-2.3 2.3a2.4 2.4 0 0 0 0 3.4Z" }],
  ["path", { d: "M7.5 13.5 10 11" }],
  ["path", { d: "M10.5 16.5 13 14" }],
  ["path", { d: "m12 6 6 6 2.3-2.3a2.4 2.4 0 0 0 0-3.4l-2.6-2.6a2.4 2.4 0 0 0-3.4 0Z" }]
];

// node_modules/lucide/dist/esm/icons/upload.js
var Upload = [
  ["path", { d: "M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" }],
  ["polyline", { points: "17 8 12 3 7 8" }],
  ["line", { x1: "12", x2: "12", y1: "3", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/usb.js
var Usb = [
  ["circle", { cx: "10", cy: "7", r: "1" }],
  ["circle", { cx: "4", cy: "20", r: "1" }],
  ["path", { d: "M4.7 19.3 19 5" }],
  ["path", { d: "m21 3-3 1 2 2Z" }],
  ["path", { d: "M9.26 7.68 5 12l2 5" }],
  ["path", { d: "m10 14 5 2 3.5-3.5" }],
  ["path", { d: "m18 12 1-1 1 1-1 1Z" }]
];

// node_modules/lucide/dist/esm/icons/user-check.js
var UserCheck = [
  ["path", { d: "M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" }],
  ["circle", { cx: "9", cy: "7", r: "4" }],
  ["polyline", { points: "16 11 18 13 22 9" }]
];

// node_modules/lucide/dist/esm/icons/user-cog.js
var UserCog = [
  ["path", { d: "M10 15H6a4 4 0 0 0-4 4v2" }],
  ["path", { d: "m14.305 16.53.923-.382" }],
  ["path", { d: "m15.228 13.852-.923-.383" }],
  ["path", { d: "m16.852 12.228-.383-.923" }],
  ["path", { d: "m16.852 17.772-.383.924" }],
  ["path", { d: "m19.148 12.228.383-.923" }],
  ["path", { d: "m19.53 18.696-.382-.924" }],
  ["path", { d: "m20.772 13.852.924-.383" }],
  ["path", { d: "m20.772 16.148.924.383" }],
  ["circle", { cx: "18", cy: "15", r: "3" }],
  ["circle", { cx: "9", cy: "7", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/user-lock.js
var UserLock = [
  ["circle", { cx: "10", cy: "7", r: "4" }],
  ["path", { d: "M10.3 15H7a4 4 0 0 0-4 4v2" }],
  ["path", { d: "M15 15.5V14a2 2 0 0 1 4 0v1.5" }],
  ["rect", { width: "8", height: "5", x: "13", y: "16", rx: ".899" }]
];

// node_modules/lucide/dist/esm/icons/user-minus.js
var UserMinus = [
  ["path", { d: "M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" }],
  ["circle", { cx: "9", cy: "7", r: "4" }],
  ["line", { x1: "22", x2: "16", y1: "11", y2: "11" }]
];

// node_modules/lucide/dist/esm/icons/user-pen.js
var UserPen = [
  ["path", { d: "M11.5 15H7a4 4 0 0 0-4 4v2" }],
  [
    "path",
    {
      d: "M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ],
  ["circle", { cx: "10", cy: "7", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/user-plus.js
var UserPlus = [
  ["path", { d: "M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" }],
  ["circle", { cx: "9", cy: "7", r: "4" }],
  ["line", { x1: "19", x2: "19", y1: "8", y2: "14" }],
  ["line", { x1: "22", x2: "16", y1: "11", y2: "11" }]
];

// node_modules/lucide/dist/esm/icons/user-round-check.js
var UserRoundCheck = [
  ["path", { d: "M2 21a8 8 0 0 1 13.292-6" }],
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["path", { d: "m16 19 2 2 4-4" }]
];

// node_modules/lucide/dist/esm/icons/user-round-cog.js
var UserRoundCog = [
  ["path", { d: "m14.305 19.53.923-.382" }],
  ["path", { d: "m15.228 16.852-.923-.383" }],
  ["path", { d: "m16.852 15.228-.383-.923" }],
  ["path", { d: "m16.852 20.772-.383.924" }],
  ["path", { d: "m19.148 15.228.383-.923" }],
  ["path", { d: "m19.53 21.696-.382-.924" }],
  ["path", { d: "M2 21a8 8 0 0 1 10.434-7.62" }],
  ["path", { d: "m20.772 16.852.924-.383" }],
  ["path", { d: "m20.772 19.148.924.383" }],
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["circle", { cx: "18", cy: "18", r: "3" }]
];

// node_modules/lucide/dist/esm/icons/user-round-minus.js
var UserRoundMinus = [
  ["path", { d: "M2 21a8 8 0 0 1 13.292-6" }],
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["path", { d: "M22 19h-6" }]
];

// node_modules/lucide/dist/esm/icons/user-round-pen.js
var UserRoundPen = [
  ["path", { d: "M2 21a8 8 0 0 1 10.821-7.487" }],
  [
    "path",
    {
      d: "M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ],
  ["circle", { cx: "10", cy: "8", r: "5" }]
];

// node_modules/lucide/dist/esm/icons/user-round-plus.js
var UserRoundPlus = [
  ["path", { d: "M2 21a8 8 0 0 1 13.292-6" }],
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["path", { d: "M19 16v6" }],
  ["path", { d: "M22 19h-6" }]
];

// node_modules/lucide/dist/esm/icons/user-round-search.js
var UserRoundSearch = [
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["path", { d: "M2 21a8 8 0 0 1 10.434-7.62" }],
  ["circle", { cx: "18", cy: "18", r: "3" }],
  ["path", { d: "m22 22-1.9-1.9" }]
];

// node_modules/lucide/dist/esm/icons/user-round-x.js
var UserRoundX = [
  ["path", { d: "M2 21a8 8 0 0 1 11.873-7" }],
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["path", { d: "m17 17 5 5" }],
  ["path", { d: "m22 17-5 5" }]
];

// node_modules/lucide/dist/esm/icons/user-round.js
var UserRound = [
  ["circle", { cx: "12", cy: "8", r: "5" }],
  ["path", { d: "M20 21a8 8 0 0 0-16 0" }]
];

// node_modules/lucide/dist/esm/icons/user-search.js
var UserSearch = [
  ["circle", { cx: "10", cy: "7", r: "4" }],
  ["path", { d: "M10.3 15H7a4 4 0 0 0-4 4v2" }],
  ["circle", { cx: "17", cy: "17", r: "3" }],
  ["path", { d: "m21 21-1.9-1.9" }]
];

// node_modules/lucide/dist/esm/icons/user-x.js
var UserX = [
  ["path", { d: "M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" }],
  ["circle", { cx: "9", cy: "7", r: "4" }],
  ["line", { x1: "17", x2: "22", y1: "8", y2: "13" }],
  ["line", { x1: "22", x2: "17", y1: "8", y2: "13" }]
];

// node_modules/lucide/dist/esm/icons/user.js
var User = [
  ["path", { d: "M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" }],
  ["circle", { cx: "12", cy: "7", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/users-round.js
var UsersRound = [
  ["path", { d: "M18 21a8 8 0 0 0-16 0" }],
  ["circle", { cx: "10", cy: "8", r: "5" }],
  ["path", { d: "M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" }]
];

// node_modules/lucide/dist/esm/icons/users.js
var Users = [
  ["path", { d: "M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" }],
  ["path", { d: "M16 3.128a4 4 0 0 1 0 7.744" }],
  ["path", { d: "M22 21v-2a4 4 0 0 0-3-3.87" }],
  ["circle", { cx: "9", cy: "7", r: "4" }]
];

// node_modules/lucide/dist/esm/icons/utensils.js
var Utensils = [
  ["path", { d: "M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2" }],
  ["path", { d: "M7 2v20" }],
  ["path", { d: "M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7" }]
];

// node_modules/lucide/dist/esm/icons/utility-pole.js
var UtilityPole = [
  ["path", { d: "M12 2v20" }],
  ["path", { d: "M2 5h20" }],
  ["path", { d: "M3 3v2" }],
  ["path", { d: "M7 3v2" }],
  ["path", { d: "M17 3v2" }],
  ["path", { d: "M21 3v2" }],
  ["path", { d: "m19 5-7 7-7-7" }]
];

// node_modules/lucide/dist/esm/icons/utensils-crossed.js
var UtensilsCrossed = [
  ["path", { d: "m16 2-2.3 2.3a3 3 0 0 0 0 4.2l1.8 1.8a3 3 0 0 0 4.2 0L22 8" }],
  ["path", { d: "M15 15 3.3 3.3a4.2 4.2 0 0 0 0 6l7.3 7.3c.7.7 2 .7 2.8 0L15 15Zm0 0 7 7" }],
  ["path", { d: "m2.1 21.8 6.4-6.3" }],
  ["path", { d: "m19 5-7 7" }]
];

// node_modules/lucide/dist/esm/icons/variable.js
var Variable = [
  ["path", { d: "M8 21s-4-3-4-9 4-9 4-9" }],
  ["path", { d: "M16 3s4 3 4 9-4 9-4 9" }],
  ["line", { x1: "15", x2: "9", y1: "9", y2: "15" }],
  ["line", { x1: "9", x2: "15", y1: "9", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/vault.js
var Vault = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["circle", { cx: "7.5", cy: "7.5", r: ".5", fill: "currentColor" }],
  ["path", { d: "m7.9 7.9 2.7 2.7" }],
  ["circle", { cx: "16.5", cy: "7.5", r: ".5", fill: "currentColor" }],
  ["path", { d: "m13.4 10.6 2.7-2.7" }],
  ["circle", { cx: "7.5", cy: "16.5", r: ".5", fill: "currentColor" }],
  ["path", { d: "m7.9 16.1 2.7-2.7" }],
  ["circle", { cx: "16.5", cy: "16.5", r: ".5", fill: "currentColor" }],
  ["path", { d: "m13.4 13.4 2.7 2.7" }],
  ["circle", { cx: "12", cy: "12", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/venetian-mask.js
var VenetianMask = [
  ["path", { d: "M18 11c-1.5 0-2.5.5-3 2" }],
  [
    "path",
    {
      d: "M4 6a2 2 0 0 0-2 2v4a5 5 0 0 0 5 5 8 8 0 0 1 5 2 8 8 0 0 1 5-2 5 5 0 0 0 5-5V8a2 2 0 0 0-2-2h-3a8 8 0 0 0-5 2 8 8 0 0 0-5-2z"
    }
  ],
  ["path", { d: "M6 11c1.5 0 2.5.5 3 2" }]
];

// node_modules/lucide/dist/esm/icons/vegan.js
var Vegan = [
  ["path", { d: "M16 8q6 0 6-6-6 0-6 6" }],
  ["path", { d: "M17.41 3.59a10 10 0 1 0 3 3" }],
  ["path", { d: "M2 2a26.6 26.6 0 0 1 10 20c.9-6.82 1.5-9.5 4-14" }]
];

// node_modules/lucide/dist/esm/icons/venus-and-mars.js
var VenusAndMars = [
  ["path", { d: "M10 20h4" }],
  ["path", { d: "M12 16v6" }],
  ["path", { d: "M17 2h4v4" }],
  ["path", { d: "m21 2-5.46 5.46" }],
  ["circle", { cx: "12", cy: "11", r: "5" }]
];

// node_modules/lucide/dist/esm/icons/venus.js
var Venus = [
  ["path", { d: "M12 15v7" }],
  ["path", { d: "M9 19h6" }],
  ["circle", { cx: "12", cy: "9", r: "6" }]
];

// node_modules/lucide/dist/esm/icons/vibrate-off.js
var VibrateOff = [
  ["path", { d: "m2 8 2 2-2 2 2 2-2 2" }],
  ["path", { d: "m22 8-2 2 2 2-2 2 2 2" }],
  ["path", { d: "M8 8v10c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2" }],
  ["path", { d: "M16 10.34V6c0-.55-.45-1-1-1h-4.34" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/vibrate.js
var Vibrate = [
  ["path", { d: "m2 8 2 2-2 2 2 2-2 2" }],
  ["path", { d: "m22 8-2 2 2 2-2 2 2 2" }],
  ["rect", { width: "8", height: "14", x: "8", y: "5", rx: "1" }]
];

// node_modules/lucide/dist/esm/icons/video.js
var Video = [
  ["path", { d: "m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5" }],
  ["rect", { x: "2", y: "6", width: "14", height: "12", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/video-off.js
var VideoOff = [
  ["path", { d: "M10.66 6H14a2 2 0 0 1 2 2v2.5l5.248-3.062A.5.5 0 0 1 22 7.87v8.196" }],
  ["path", { d: "M16 16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/videotape.js
var Videotape = [
  ["rect", { width: "20", height: "16", x: "2", y: "4", rx: "2" }],
  ["path", { d: "M2 8h20" }],
  ["circle", { cx: "8", cy: "14", r: "2" }],
  ["path", { d: "M8 12h8" }],
  ["circle", { cx: "16", cy: "14", r: "2" }]
];

// node_modules/lucide/dist/esm/icons/view.js
var View = [
  ["path", { d: "M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2" }],
  ["path", { d: "M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2" }],
  ["circle", { cx: "12", cy: "12", r: "1" }],
  [
    "path",
    {
      d: "M18.944 12.33a1 1 0 0 0 0-.66 7.5 7.5 0 0 0-13.888 0 1 1 0 0 0 0 .66 7.5 7.5 0 0 0 13.888 0"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/voicemail.js
var Voicemail = [
  ["circle", { cx: "6", cy: "12", r: "4" }],
  ["circle", { cx: "18", cy: "12", r: "4" }],
  ["line", { x1: "6", x2: "18", y1: "16", y2: "16" }]
];

// node_modules/lucide/dist/esm/icons/volleyball.js
var Volleyball = [
  ["path", { d: "M11.1 7.1a16.55 16.55 0 0 1 10.9 4" }],
  ["path", { d: "M12 12a12.6 12.6 0 0 1-8.7 5" }],
  ["path", { d: "M16.8 13.6a16.55 16.55 0 0 1-9 7.5" }],
  ["path", { d: "M20.7 17a12.8 12.8 0 0 0-8.7-5 13.3 13.3 0 0 1 0-10" }],
  ["path", { d: "M6.3 3.8a16.55 16.55 0 0 0 1.9 11.5" }],
  ["circle", { cx: "12", cy: "12", r: "10" }]
];

// node_modules/lucide/dist/esm/icons/volume-1.js
var Volume1 = [
  [
    "path",
    {
      d: "M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"
    }
  ],
  ["path", { d: "M16 9a5 5 0 0 1 0 6" }]
];

// node_modules/lucide/dist/esm/icons/volume-2.js
var Volume2 = [
  [
    "path",
    {
      d: "M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"
    }
  ],
  ["path", { d: "M16 9a5 5 0 0 1 0 6" }],
  ["path", { d: "M19.364 18.364a9 9 0 0 0 0-12.728" }]
];

// node_modules/lucide/dist/esm/icons/volume-off.js
var VolumeOff = [
  ["path", { d: "M16 9a5 5 0 0 1 .95 2.293" }],
  ["path", { d: "M19.364 5.636a9 9 0 0 1 1.889 9.96" }],
  ["path", { d: "m2 2 20 20" }],
  [
    "path",
    {
      d: "m7 7-.587.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298V11"
    }
  ],
  ["path", { d: "M9.828 4.172A.686.686 0 0 1 11 4.657v.686" }]
];

// node_modules/lucide/dist/esm/icons/volume-x.js
var VolumeX = [
  [
    "path",
    {
      d: "M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"
    }
  ],
  ["line", { x1: "22", x2: "16", y1: "9", y2: "15" }],
  ["line", { x1: "16", x2: "22", y1: "9", y2: "15" }]
];

// node_modules/lucide/dist/esm/icons/volume.js
var Volume = [
  [
    "path",
    {
      d: "M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/vote.js
var Vote = [
  ["path", { d: "m9 12 2 2 4-4" }],
  ["path", { d: "M5 7c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v12H5V7Z" }],
  ["path", { d: "M22 19H2" }]
];

// node_modules/lucide/dist/esm/icons/wallet-cards.js
var WalletCards = [
  ["rect", { width: "18", height: "18", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2" }],
  [
    "path",
    { d: "M3 11h3c.8 0 1.6.3 2.1.9l1.1.9c1.6 1.6 4.1 1.6 5.7 0l1.1-.9c.5-.5 1.3-.9 2.1-.9H21" }
  ]
];

// node_modules/lucide/dist/esm/icons/wallet-minimal.js
var WalletMinimal = [
  ["path", { d: "M17 14h.01" }],
  ["path", { d: "M7 7h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14" }]
];

// node_modules/lucide/dist/esm/icons/wallet.js
var Wallet = [
  [
    "path",
    {
      d: "M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"
    }
  ],
  ["path", { d: "M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" }]
];

// node_modules/lucide/dist/esm/icons/wallpaper.js
var Wallpaper = [
  ["circle", { cx: "8", cy: "9", r: "2" }],
  [
    "path",
    {
      d: "m9 17 6.1-6.1a2 2 0 0 1 2.81.01L22 15V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2"
    }
  ],
  ["path", { d: "M8 21h8" }],
  ["path", { d: "M12 17v4" }]
];

// node_modules/lucide/dist/esm/icons/wand-sparkles.js
var WandSparkles = [
  [
    "path",
    {
      d: "m21.64 3.64-1.28-1.28a1.21 1.21 0 0 0-1.72 0L2.36 18.64a1.21 1.21 0 0 0 0 1.72l1.28 1.28a1.2 1.2 0 0 0 1.72 0L21.64 5.36a1.2 1.2 0 0 0 0-1.72"
    }
  ],
  ["path", { d: "m14 7 3 3" }],
  ["path", { d: "M5 6v4" }],
  ["path", { d: "M19 14v4" }],
  ["path", { d: "M10 2v2" }],
  ["path", { d: "M7 8H3" }],
  ["path", { d: "M21 16h-4" }],
  ["path", { d: "M11 3H9" }]
];

// node_modules/lucide/dist/esm/icons/wand.js
var Wand = [
  ["path", { d: "M15 4V2" }],
  ["path", { d: "M15 16v-2" }],
  ["path", { d: "M8 9h2" }],
  ["path", { d: "M20 9h2" }],
  ["path", { d: "M17.8 11.8 19 13" }],
  ["path", { d: "M15 9h.01" }],
  ["path", { d: "M17.8 6.2 19 5" }],
  ["path", { d: "m3 21 9-9" }],
  ["path", { d: "M12.2 6.2 11 5" }]
];

// node_modules/lucide/dist/esm/icons/warehouse.js
var Warehouse = [
  [
    "path",
    {
      d: "M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35A2 2 0 0 1 3.26 6.5l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z"
    }
  ],
  ["path", { d: "M6 18h12" }],
  ["path", { d: "M6 14h12" }],
  ["rect", { width: "12", height: "12", x: "6", y: "10" }]
];

// node_modules/lucide/dist/esm/icons/washing-machine.js
var WashingMachine = [
  ["path", { d: "M3 6h3" }],
  ["path", { d: "M17 6h.01" }],
  ["rect", { width: "18", height: "20", x: "3", y: "2", rx: "2" }],
  ["circle", { cx: "12", cy: "13", r: "5" }],
  ["path", { d: "M12 18a2.5 2.5 0 0 0 0-5 2.5 2.5 0 0 1 0-5" }]
];

// node_modules/lucide/dist/esm/icons/watch.js
var Watch = [
  ["circle", { cx: "12", cy: "12", r: "6" }],
  ["polyline", { points: "12 10 12 12 13 13" }],
  ["path", { d: "m16.13 7.66-.81-4.05a2 2 0 0 0-2-1.61h-2.68a2 2 0 0 0-2 1.61l-.78 4.05" }],
  ["path", { d: "m7.88 16.36.8 4a2 2 0 0 0 2 1.61h2.72a2 2 0 0 0 2-1.61l.81-4.05" }]
];

// node_modules/lucide/dist/esm/icons/waves-ladder.js
var WavesLadder = [
  ["path", { d: "M19 5a2 2 0 0 0-2 2v11" }],
  [
    "path",
    {
      d: "M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"
    }
  ],
  ["path", { d: "M7 13h10" }],
  ["path", { d: "M7 9h10" }],
  ["path", { d: "M9 5a2 2 0 0 0-2 2v11" }]
];

// node_modules/lucide/dist/esm/icons/waves.js
var Waves = [
  [
    "path",
    { d: "M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" }
  ],
  [
    "path",
    {
      d: "M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"
    }
  ],
  [
    "path",
    {
      d: "M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/waypoints.js
var Waypoints = [
  ["circle", { cx: "12", cy: "4.5", r: "2.5" }],
  ["path", { d: "m10.2 6.3-3.9 3.9" }],
  ["circle", { cx: "4.5", cy: "12", r: "2.5" }],
  ["path", { d: "M7 12h10" }],
  ["circle", { cx: "19.5", cy: "12", r: "2.5" }],
  ["path", { d: "m13.8 17.7 3.9-3.9" }],
  ["circle", { cx: "12", cy: "19.5", r: "2.5" }]
];

// node_modules/lucide/dist/esm/icons/webcam.js
var Webcam = [
  ["circle", { cx: "12", cy: "10", r: "8" }],
  ["circle", { cx: "12", cy: "10", r: "3" }],
  ["path", { d: "M7 22h10" }],
  ["path", { d: "M12 22v-4" }]
];

// node_modules/lucide/dist/esm/icons/webhook-off.js
var WebhookOff = [
  ["path", { d: "M17 17h-5c-1.09-.02-1.94.92-2.5 1.9A3 3 0 1 1 2.57 15" }],
  ["path", { d: "M9 3.4a4 4 0 0 1 6.52.66" }],
  ["path", { d: "m6 17 3.1-5.8a2.5 2.5 0 0 0 .057-2.05" }],
  ["path", { d: "M20.3 20.3a4 4 0 0 1-2.3.7" }],
  ["path", { d: "M18.6 13a4 4 0 0 1 3.357 3.414" }],
  ["path", { d: "m12 6 .6 1" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/webhook.js
var Webhook = [
  ["path", { d: "M18 16.98h-5.99c-1.1 0-1.95.94-2.48 1.9A4 4 0 0 1 2 17c.01-.7.2-1.4.57-2" }],
  ["path", { d: "m6 17 3.13-5.78c.53-.97.1-2.18-.5-3.1a4 4 0 1 1 6.89-4.06" }],
  ["path", { d: "m12 6 3.13 5.73C15.66 12.7 16.9 13 18 13a4 4 0 0 1 0 8" }]
];

// node_modules/lucide/dist/esm/icons/weight.js
var Weight = [
  ["circle", { cx: "12", cy: "5", r: "3" }],
  [
    "path",
    {
      d: "M6.5 8a2 2 0 0 0-1.905 1.46L2.1 18.5A2 2 0 0 0 4 21h16a2 2 0 0 0 1.925-2.54L19.4 9.5A2 2 0 0 0 17.48 8Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/wheat-off.js
var WheatOff = [
  ["path", { d: "m2 22 10-10" }],
  ["path", { d: "m16 8-1.17 1.17" }],
  [
    "path",
    { d: "M3.47 12.53 5 11l1.53 1.53a3.5 3.5 0 0 1 0 4.94L5 19l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z" }
  ],
  ["path", { d: "m8 8-.53.53a3.5 3.5 0 0 0 0 4.94L9 15l1.53-1.53c.55-.55.88-1.25.98-1.97" }],
  ["path", { d: "M10.91 5.26c.15-.26.34-.51.56-.73L13 3l1.53 1.53a3.5 3.5 0 0 1 .28 4.62" }],
  ["path", { d: "M20 2h2v2a4 4 0 0 1-4 4h-2V6a4 4 0 0 1 4-4Z" }],
  [
    "path",
    {
      d: "M11.47 17.47 13 19l-1.53 1.53a3.5 3.5 0 0 1-4.94 0L5 19l1.53-1.53a3.5 3.5 0 0 1 4.94 0Z"
    }
  ],
  ["path", { d: "m16 16-.53.53a3.5 3.5 0 0 1-4.94 0L9 15l1.53-1.53a3.49 3.49 0 0 1 1.97-.98" }],
  ["path", { d: "M18.74 13.09c.26-.15.51-.34.73-.56L21 11l-1.53-1.53a3.5 3.5 0 0 0-4.62-.28" }],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/wheat.js
var Wheat = [
  ["path", { d: "M2 22 16 8" }],
  [
    "path",
    { d: "M3.47 12.53 5 11l1.53 1.53a3.5 3.5 0 0 1 0 4.94L5 19l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z" }
  ],
  [
    "path",
    { d: "M7.47 8.53 9 7l1.53 1.53a3.5 3.5 0 0 1 0 4.94L9 15l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z" }
  ],
  [
    "path",
    { d: "M11.47 4.53 13 3l1.53 1.53a3.5 3.5 0 0 1 0 4.94L13 11l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z" }
  ],
  ["path", { d: "M20 2h2v2a4 4 0 0 1-4 4h-2V6a4 4 0 0 1 4-4Z" }],
  [
    "path",
    {
      d: "M11.47 17.47 13 19l-1.53 1.53a3.5 3.5 0 0 1-4.94 0L5 19l1.53-1.53a3.5 3.5 0 0 1 4.94 0Z"
    }
  ],
  [
    "path",
    {
      d: "M15.47 13.47 17 15l-1.53 1.53a3.5 3.5 0 0 1-4.94 0L9 15l1.53-1.53a3.5 3.5 0 0 1 4.94 0Z"
    }
  ],
  [
    "path",
    {
      d: "M19.47 9.47 21 11l-1.53 1.53a3.5 3.5 0 0 1-4.94 0L13 11l1.53-1.53a3.5 3.5 0 0 1 4.94 0Z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/whole-word.js
var WholeWord = [
  ["circle", { cx: "7", cy: "12", r: "3" }],
  ["path", { d: "M10 9v6" }],
  ["circle", { cx: "17", cy: "12", r: "3" }],
  ["path", { d: "M14 7v8" }],
  ["path", { d: "M22 17v1c0 .5-.5 1-1 1H3c-.5 0-1-.5-1-1v-1" }]
];

// node_modules/lucide/dist/esm/icons/wifi-high.js
var WifiHigh = [
  ["path", { d: "M12 20h.01" }],
  ["path", { d: "M5 12.859a10 10 0 0 1 14 0" }],
  ["path", { d: "M8.5 16.429a5 5 0 0 1 7 0" }]
];

// node_modules/lucide/dist/esm/icons/wifi-low.js
var WifiLow = [
  ["path", { d: "M12 20h.01" }],
  ["path", { d: "M8.5 16.429a5 5 0 0 1 7 0" }]
];

// node_modules/lucide/dist/esm/icons/wifi-off.js
var WifiOff = [
  ["path", { d: "M12 20h.01" }],
  ["path", { d: "M8.5 16.429a5 5 0 0 1 7 0" }],
  ["path", { d: "M5 12.859a10 10 0 0 1 5.17-2.69" }],
  ["path", { d: "M19 12.859a10 10 0 0 0-2.007-1.523" }],
  ["path", { d: "M2 8.82a15 15 0 0 1 4.177-2.643" }],
  ["path", { d: "M22 8.82a15 15 0 0 0-11.288-3.764" }],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/wifi-pen.js
var WifiPen = [
  ["path", { d: "M2 8.82a15 15 0 0 1 20 0" }],
  [
    "path",
    {
      d: "M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"
    }
  ],
  ["path", { d: "M5 12.859a10 10 0 0 1 10.5-2.222" }],
  ["path", { d: "M8.5 16.429a5 5 0 0 1 3-1.406" }]
];

// node_modules/lucide/dist/esm/icons/wifi-zero.js
var WifiZero = [["path", { d: "M12 20h.01" }]];

// node_modules/lucide/dist/esm/icons/wifi.js
var Wifi = [
  ["path", { d: "M12 20h.01" }],
  ["path", { d: "M2 8.82a15 15 0 0 1 20 0" }],
  ["path", { d: "M5 12.859a10 10 0 0 1 14 0" }],
  ["path", { d: "M8.5 16.429a5 5 0 0 1 7 0" }]
];

// node_modules/lucide/dist/esm/icons/wind-arrow-down.js
var WindArrowDown = [
  ["path", { d: "M10 2v8" }],
  ["path", { d: "M12.8 21.6A2 2 0 1 0 14 18H2" }],
  ["path", { d: "M17.5 10a2.5 2.5 0 1 1 2 4H2" }],
  ["path", { d: "m6 6 4 4 4-4" }]
];

// node_modules/lucide/dist/esm/icons/wind.js
var Wind = [
  ["path", { d: "M12.8 19.6A2 2 0 1 0 14 16H2" }],
  ["path", { d: "M17.5 8a2.5 2.5 0 1 1 2 4H2" }],
  ["path", { d: "M9.8 4.4A2 2 0 1 1 11 8H2" }]
];

// node_modules/lucide/dist/esm/icons/wine-off.js
var WineOff = [
  ["path", { d: "M8 22h8" }],
  ["path", { d: "M7 10h3m7 0h-1.343" }],
  ["path", { d: "M12 15v7" }],
  [
    "path",
    {
      d: "M7.307 7.307A12.33 12.33 0 0 0 7 10a5 5 0 0 0 7.391 4.391M8.638 2.981C8.75 2.668 8.872 2.34 9 2h6c1.5 4 2 6 2 8 0 .407-.05.809-.145 1.198"
    }
  ],
  ["line", { x1: "2", x2: "22", y1: "2", y2: "22" }]
];

// node_modules/lucide/dist/esm/icons/workflow.js
var Workflow = [
  ["rect", { width: "8", height: "8", x: "3", y: "3", rx: "2" }],
  ["path", { d: "M7 11v4a2 2 0 0 0 2 2h4" }],
  ["rect", { width: "8", height: "8", x: "13", y: "13", rx: "2" }]
];

// node_modules/lucide/dist/esm/icons/wine.js
var Wine = [
  ["path", { d: "M8 22h8" }],
  ["path", { d: "M7 10h10" }],
  ["path", { d: "M12 15v7" }],
  ["path", { d: "M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5Z" }]
];

// node_modules/lucide/dist/esm/icons/worm.js
var Worm = [
  ["path", { d: "m19 12-1.5 3" }],
  ["path", { d: "M19.63 18.81 22 20" }],
  [
    "path",
    {
      d: "M6.47 8.23a1.68 1.68 0 0 1 2.44 1.93l-.64 2.08a6.76 6.76 0 0 0 10.16 7.67l.42-.27a1 1 0 1 0-2.73-4.21l-.42.27a1.76 1.76 0 0 1-2.63-1.99l.64-2.08A6.66 6.66 0 0 0 3.94 3.9l-.7.4a1 1 0 1 0 2.55 4.34z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/wrap-text.js
var WrapText = [
  ["line", { x1: "3", x2: "21", y1: "6", y2: "6" }],
  ["path", { d: "M3 12h15a3 3 0 1 1 0 6h-4" }],
  ["polyline", { points: "16 16 14 18 16 20" }],
  ["line", { x1: "3", x2: "10", y1: "18", y2: "18" }]
];

// node_modules/lucide/dist/esm/icons/wrench.js
var Wrench = [
  [
    "path",
    {
      d: "M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/x.js
var X = [
  ["path", { d: "M18 6 6 18" }],
  ["path", { d: "m6 6 12 12" }]
];

// node_modules/lucide/dist/esm/icons/youtube.js
var Youtube = [
  [
    "path",
    {
      d: "M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"
    }
  ],
  ["path", { d: "m10 15 5-3-5-3z" }]
];

// node_modules/lucide/dist/esm/icons/zap-off.js
var ZapOff = [
  ["path", { d: "M10.513 4.856 13.12 2.17a.5.5 0 0 1 .86.46l-1.377 4.317" }],
  ["path", { d: "M15.656 10H20a1 1 0 0 1 .78 1.63l-1.72 1.773" }],
  [
    "path",
    {
      d: "M16.273 16.273 10.88 21.83a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14H4a1 1 0 0 1-.78-1.63l4.507-4.643"
    }
  ],
  ["path", { d: "m2 2 20 20" }]
];

// node_modules/lucide/dist/esm/icons/zap.js
var Zap = [
  [
    "path",
    {
      d: "M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"
    }
  ]
];

// node_modules/lucide/dist/esm/icons/zoom-in.js
var ZoomIn = [
  ["circle", { cx: "11", cy: "11", r: "8" }],
  ["line", { x1: "21", x2: "16.65", y1: "21", y2: "16.65" }],
  ["line", { x1: "11", x2: "11", y1: "8", y2: "14" }],
  ["line", { x1: "8", x2: "14", y1: "11", y2: "11" }]
];

// node_modules/lucide/dist/esm/icons/zoom-out.js
var ZoomOut = [
  ["circle", { cx: "11", cy: "11", r: "8" }],
  ["line", { x1: "21", x2: "16.65", y1: "21", y2: "16.65" }],
  ["line", { x1: "8", x2: "14", y1: "11", y2: "11" }]
];

// node_modules/lucide/dist/esm/lucide.js
var createIcons = ({ icons = {}, nameAttr = "data-lucide", attrs = {} } = {}) => {
  if (!Object.values(icons).length) {
    throw new Error(
      "Please provide an icons object.\nIf you want to use all the icons you can import it like:\n `import { createIcons, icons } from 'lucide';\nlucide.createIcons({icons});`"
    );
  }
  if (typeof document === "undefined") {
    throw new Error("`createIcons()` only works in a browser environment.");
  }
  const elementsToReplace = document.querySelectorAll(`[${nameAttr}]`);
  Array.from(elementsToReplace).forEach(
    (element) => replaceElement(element, { nameAttr, icons, attrs })
  );
  if (nameAttr === "data-lucide") {
    const deprecatedElements = document.querySelectorAll("[icon-name]");
    if (deprecatedElements.length > 0) {
      console.warn(
        "[Lucide] Some icons were found with the now deprecated icon-name attribute. These will still be replaced for backwards compatibility, but will no longer be supported in v1.0 and you should switch to data-lucide"
      );
      Array.from(deprecatedElements).forEach(
        (element) => replaceElement(element, { nameAttr: "icon-name", icons, attrs })
      );
    }
  }
};
export {
  AArrowDown,
  AArrowUp,
  ALargeSmall,
  Accessibility,
  Activity,
  SquareActivity as ActivitySquare,
  AirVent,
  Airplay,
  AlarmClockCheck as AlarmCheck,
  AlarmClock,
  AlarmClockCheck,
  AlarmClockMinus,
  AlarmClockOff,
  AlarmClockPlus,
  AlarmClockMinus as AlarmMinus,
  AlarmClockPlus as AlarmPlus,
  AlarmSmoke,
  Album,
  CircleAlert as AlertCircle,
  OctagonAlert as AlertOctagon,
  TriangleAlert as AlertTriangle,
  AlignCenter,
  AlignCenterHorizontal,
  AlignCenterVertical,
  AlignEndHorizontal,
  AlignEndVertical,
  AlignHorizontalDistributeCenter,
  AlignHorizontalDistributeEnd,
  AlignHorizontalDistributeStart,
  AlignHorizontalJustifyCenter,
  AlignHorizontalJustifyEnd,
  AlignHorizontalJustifyStart,
  AlignHorizontalSpaceAround,
  AlignHorizontalSpaceBetween,
  AlignJustify,
  AlignLeft,
  AlignRight,
  AlignStartHorizontal,
  AlignStartVertical,
  AlignVerticalDistributeCenter,
  AlignVerticalDistributeEnd,
  AlignVerticalDistributeStart,
  AlignVerticalJustifyCenter,
  AlignVerticalJustifyEnd,
  AlignVerticalJustifyStart,
  AlignVerticalSpaceAround,
  AlignVerticalSpaceBetween,
  Ambulance,
  Ampersand,
  Ampersands,
  Amphora,
  Anchor,
  Angry,
  Annoyed,
  Antenna,
  Anvil,
  Aperture,
  AppWindow,
  AppWindowMac,
  Apple,
  Archive,
  ArchiveRestore,
  ArchiveX,
  ChartArea as AreaChart,
  Armchair,
  ArrowBigDown,
  ArrowBigDownDash,
  ArrowBigLeft,
  ArrowBigLeftDash,
  ArrowBigRight,
  ArrowBigRightDash,
  ArrowBigUp,
  ArrowBigUpDash,
  ArrowDown,
  ArrowDown01,
  ArrowDown10,
  ArrowDownAZ,
  ArrowDownAZ as ArrowDownAz,
  CircleArrowDown as ArrowDownCircle,
  ArrowDownFromLine,
  ArrowDownLeft,
  CircleArrowOutDownLeft as ArrowDownLeftFromCircle,
  SquareArrowOutDownLeft as ArrowDownLeftFromSquare,
  SquareArrowDownLeft as ArrowDownLeftSquare,
  ArrowDownNarrowWide,
  ArrowDownRight,
  CircleArrowOutDownRight as ArrowDownRightFromCircle,
  SquareArrowOutDownRight as ArrowDownRightFromSquare,
  SquareArrowDownRight as ArrowDownRightSquare,
  SquareArrowDown as ArrowDownSquare,
  ArrowDownToDot,
  ArrowDownToLine,
  ArrowDownUp,
  ArrowDownWideNarrow,
  ArrowDownZA,
  ArrowDownZA as ArrowDownZa,
  ArrowLeft,
  CircleArrowLeft as ArrowLeftCircle,
  ArrowLeftFromLine,
  ArrowLeftRight,
  SquareArrowLeft as ArrowLeftSquare,
  ArrowLeftToLine,
  ArrowRight,
  CircleArrowRight as ArrowRightCircle,
  ArrowRightFromLine,
  ArrowRightLeft,
  SquareArrowRight as ArrowRightSquare,
  ArrowRightToLine,
  ArrowUp,
  ArrowUp01,
  ArrowUp10,
  ArrowUpAZ,
  ArrowUpAZ as ArrowUpAz,
  CircleArrowUp as ArrowUpCircle,
  ArrowUpDown,
  ArrowUpFromDot,
  ArrowUpFromLine,
  ArrowUpLeft,
  CircleArrowOutUpLeft as ArrowUpLeftFromCircle,
  SquareArrowOutUpLeft as ArrowUpLeftFromSquare,
  SquareArrowUpLeft as ArrowUpLeftSquare,
  ArrowUpNarrowWide,
  ArrowUpRight,
  CircleArrowOutUpRight as ArrowUpRightFromCircle,
  SquareArrowOutUpRight as ArrowUpRightFromSquare,
  SquareArrowUpRight as ArrowUpRightSquare,
  SquareArrowUp as ArrowUpSquare,
  ArrowUpToLine,
  ArrowUpWideNarrow,
  ArrowUpZA,
  ArrowUpZA as ArrowUpZa,
  ArrowsUpFromLine,
  Asterisk,
  SquareAsterisk as AsteriskSquare,
  AtSign,
  Atom,
  AudioLines,
  AudioWaveform,
  Award,
  Axe,
  Axis3d as Axis3D,
  Axis3d,
  Baby,
  Backpack,
  Badge,
  BadgeAlert,
  BadgeCent,
  BadgeCheck,
  BadgeDollarSign,
  BadgeEuro,
  BadgeHelp,
  BadgeIndianRupee,
  BadgeInfo,
  BadgeJapaneseYen,
  BadgeMinus,
  BadgePercent,
  BadgePlus,
  BadgePoundSterling,
  BadgeRussianRuble,
  BadgeSwissFranc,
  BadgeX,
  BaggageClaim,
  Ban,
  Banana,
  Bandage,
  Banknote,
  BanknoteArrowDown,
  BanknoteArrowUp,
  BanknoteX,
  ChartNoAxesColumnIncreasing as BarChart,
  ChartNoAxesColumn as BarChart2,
  ChartColumn as BarChart3,
  ChartColumnIncreasing as BarChart4,
  ChartColumnBig as BarChartBig,
  ChartBar as BarChartHorizontal,
  ChartBarBig as BarChartHorizontalBig,
  Barcode,
  Baseline,
  Bath,
  Battery,
  BatteryCharging,
  BatteryFull,
  BatteryLow,
  BatteryMedium,
  BatteryPlus,
  BatteryWarning,
  Beaker,
  Bean,
  BeanOff,
  Bed,
  BedDouble,
  BedSingle,
  Beef,
  Beer,
  BeerOff,
  Bell,
  BellDot,
  BellElectric,
  BellMinus,
  BellOff,
  BellPlus,
  BellRing,
  BetweenHorizontalEnd as BetweenHorizonalEnd,
  BetweenHorizontalStart as BetweenHorizonalStart,
  BetweenHorizontalEnd,
  BetweenHorizontalStart,
  BetweenVerticalEnd,
  BetweenVerticalStart,
  BicepsFlexed,
  Bike,
  Binary,
  Binoculars,
  Biohazard,
  Bird,
  Bitcoin,
  Blend,
  Blinds,
  Blocks,
  Bluetooth,
  BluetoothConnected,
  BluetoothOff,
  BluetoothSearching,
  Bold,
  Bolt,
  Bomb,
  Bone,
  Book,
  BookA,
  BookAudio,
  BookCheck,
  BookCopy,
  BookDashed,
  BookDown,
  BookHeadphones,
  BookHeart,
  BookImage,
  BookKey,
  BookLock,
  BookMarked,
  BookMinus,
  BookOpen,
  BookOpenCheck,
  BookOpenText,
  BookPlus,
  BookDashed as BookTemplate,
  BookText,
  BookType,
  BookUp,
  BookUp2,
  BookUser,
  BookX,
  Bookmark,
  BookmarkCheck,
  BookmarkMinus,
  BookmarkPlus,
  BookmarkX,
  BoomBox,
  Bot,
  BotMessageSquare,
  BotOff,
  BowArrow,
  Box,
  SquareDashed as BoxSelect,
  Boxes,
  Braces,
  Brackets,
  Brain,
  BrainCircuit,
  BrainCog,
  BrickWall,
  BrickWallFire,
  Briefcase,
  BriefcaseBusiness,
  BriefcaseConveyorBelt,
  BriefcaseMedical,
  BringToFront,
  Brush,
  BrushCleaning,
  Bubbles,
  Bug,
  BugOff,
  BugPlay,
  Building,
  Building2,
  Bus,
  BusFront,
  Cable,
  CableCar,
  Cake,
  CakeSlice,
  Calculator,
  Calendar,
  Calendar1,
  CalendarArrowDown,
  CalendarArrowUp,
  CalendarCheck,
  CalendarCheck2,
  CalendarClock,
  CalendarCog,
  CalendarDays,
  CalendarFold,
  CalendarHeart,
  CalendarMinus,
  CalendarMinus2,
  CalendarOff,
  CalendarPlus,
  CalendarPlus2,
  CalendarRange,
  CalendarSearch,
  CalendarSync,
  CalendarX,
  CalendarX2,
  Camera,
  CameraOff,
  ChartCandlestick as CandlestickChart,
  Candy,
  CandyCane,
  CandyOff,
  Cannabis,
  Captions,
  CaptionsOff,
  Car,
  CarFront,
  CarTaxiFront,
  Caravan,
  Carrot,
  CaseLower,
  CaseSensitive,
  CaseUpper,
  CassetteTape,
  Cast,
  Castle,
  Cat,
  Cctv,
  ChartArea,
  ChartBar,
  ChartBarBig,
  ChartBarDecreasing,
  ChartBarIncreasing,
  ChartBarStacked,
  ChartCandlestick,
  ChartColumn,
  ChartColumnBig,
  ChartColumnDecreasing,
  ChartColumnIncreasing,
  ChartColumnStacked,
  ChartGantt,
  ChartLine,
  ChartNetwork,
  ChartNoAxesColumn,
  ChartNoAxesColumnDecreasing,
  ChartNoAxesColumnIncreasing,
  ChartNoAxesCombined,
  ChartNoAxesGantt,
  ChartPie,
  ChartScatter,
  ChartSpline,
  Check,
  CheckCheck,
  CircleCheckBig as CheckCircle,
  CircleCheck as CheckCircle2,
  SquareCheckBig as CheckSquare,
  SquareCheck as CheckSquare2,
  ChefHat,
  Cherry,
  ChevronDown,
  CircleChevronDown as ChevronDownCircle,
  SquareChevronDown as ChevronDownSquare,
  ChevronFirst,
  ChevronLast,
  ChevronLeft,
  CircleChevronLeft as ChevronLeftCircle,
  SquareChevronLeft as ChevronLeftSquare,
  ChevronRight,
  CircleChevronRight as ChevronRightCircle,
  SquareChevronRight as ChevronRightSquare,
  ChevronUp,
  CircleChevronUp as ChevronUpCircle,
  SquareChevronUp as ChevronUpSquare,
  ChevronsDown,
  ChevronsDownUp,
  ChevronsLeft,
  ChevronsLeftRight,
  ChevronsLeftRightEllipsis,
  ChevronsRight,
  ChevronsRightLeft,
  ChevronsUp,
  ChevronsUpDown,
  Chrome,
  Church,
  Cigarette,
  CigaretteOff,
  Circle,
  CircleAlert,
  CircleArrowDown,
  CircleArrowLeft,
  CircleArrowOutDownLeft,
  CircleArrowOutDownRight,
  CircleArrowOutUpLeft,
  CircleArrowOutUpRight,
  CircleArrowRight,
  CircleArrowUp,
  CircleCheck,
  CircleCheckBig,
  CircleChevronDown,
  CircleChevronLeft,
  CircleChevronRight,
  CircleChevronUp,
  CircleDashed,
  CircleDivide,
  CircleDollarSign,
  CircleDot,
  CircleDotDashed,
  CircleEllipsis,
  CircleEqual,
  CircleFadingArrowUp,
  CircleFadingPlus,
  CircleGauge,
  CircleHelp,
  CircleMinus,
  CircleOff,
  CircleParking,
  CircleParkingOff,
  CirclePause,
  CirclePercent,
  CirclePlay,
  CirclePlus,
  CirclePower,
  CircleSlash,
  CircleSlash2,
  CircleSlash2 as CircleSlashed,
  CircleSmall,
  CircleStop,
  CircleUser,
  CircleUserRound,
  CircleX,
  CircuitBoard,
  Citrus,
  Clapperboard,
  Clipboard,
  ClipboardCheck,
  ClipboardCopy,
  ClipboardPen as ClipboardEdit,
  ClipboardList,
  ClipboardMinus,
  ClipboardPaste,
  ClipboardPen,
  ClipboardPenLine,
  ClipboardPlus,
  ClipboardPenLine as ClipboardSignature,
  ClipboardType,
  ClipboardX,
  Clock,
  Clock1,
  Clock10,
  Clock11,
  Clock12,
  Clock2,
  Clock3,
  Clock4,
  Clock5,
  Clock6,
  Clock7,
  Clock8,
  Clock9,
  ClockAlert,
  ClockArrowDown,
  ClockArrowUp,
  ClockFading,
  ClockPlus,
  Cloud,
  CloudAlert,
  CloudCog,
  CloudDownload,
  CloudDrizzle,
  CloudFog,
  CloudHail,
  CloudLightning,
  CloudMoon,
  CloudMoonRain,
  CloudOff,
  CloudRain,
  CloudRainWind,
  CloudSnow,
  CloudSun,
  CloudSunRain,
  CloudUpload,
  Cloudy,
  Clover,
  Club,
  Code,
  CodeXml as Code2,
  SquareCode as CodeSquare,
  CodeXml,
  Codepen,
  Codesandbox,
  Coffee,
  Cog,
  Coins,
  Columns2 as Columns,
  Columns2,
  Columns3,
  Columns3Cog,
  Columns4,
  Columns3Cog as ColumnsSettings,
  Combine,
  Command,
  Compass,
  Component,
  Computer,
  ConciergeBell,
  Cone,
  Construction,
  Contact,
  ContactRound as Contact2,
  ContactRound,
  Container,
  Contrast,
  Cookie,
  CookingPot,
  Copy,
  CopyCheck,
  CopyMinus,
  CopyPlus,
  CopySlash,
  CopyX,
  Copyleft,
  Copyright,
  CornerDownLeft,
  CornerDownRight,
  CornerLeftDown,
  CornerLeftUp,
  CornerRightDown,
  CornerRightUp,
  CornerUpLeft,
  CornerUpRight,
  Cpu,
  CreativeCommons,
  CreditCard,
  Croissant,
  Crop,
  Cross,
  Crosshair,
  Crown,
  Cuboid,
  CupSoda,
  Braces as CurlyBraces,
  Currency,
  Cylinder,
  Dam,
  Database,
  DatabaseBackup,
  DatabaseZap,
  DecimalsArrowLeft,
  DecimalsArrowRight,
  Delete,
  Dessert,
  Diameter,
  Diamond,
  DiamondMinus,
  DiamondPercent,
  DiamondPlus,
  Dice1,
  Dice2,
  Dice3,
  Dice4,
  Dice5,
  Dice6,
  Dices,
  Diff,
  Disc,
  Disc2,
  Disc3,
  DiscAlbum,
  Divide,
  CircleDivide as DivideCircle,
  SquareDivide as DivideSquare,
  Dna,
  DnaOff,
  Dock,
  Dog,
  DollarSign,
  Donut,
  DoorClosed,
  DoorClosedLocked,
  DoorOpen,
  Dot,
  SquareDot as DotSquare,
  Download,
  CloudDownload as DownloadCloud,
  DraftingCompass,
  Drama,
  Dribbble,
  Drill,
  Droplet,
  DropletOff,
  Droplets,
  Drum,
  Drumstick,
  Dumbbell,
  Ear,
  EarOff,
  Earth,
  EarthLock,
  Eclipse,
  SquarePen as Edit,
  Pen as Edit2,
  PenLine as Edit3,
  Egg,
  EggFried,
  EggOff,
  Ellipsis,
  EllipsisVertical,
  Equal,
  EqualApproximately,
  EqualNot,
  SquareEqual as EqualSquare,
  Eraser,
  EthernetPort,
  Euro,
  Expand,
  ExternalLink,
  Eye,
  EyeClosed,
  EyeOff,
  Facebook,
  Factory,
  Fan,
  FastForward,
  Feather,
  Fence,
  FerrisWheel,
  Figma,
  File,
  FileArchive,
  FileAudio,
  FileAudio2,
  FileAxis3d as FileAxis3D,
  FileAxis3d,
  FileBadge,
  FileBadge2,
  FileChartColumnIncreasing as FileBarChart,
  FileChartColumn as FileBarChart2,
  FileBox,
  FileChartColumn,
  FileChartColumnIncreasing,
  FileChartLine,
  FileChartPie,
  FileCheck,
  FileCheck2,
  FileClock,
  FileCode,
  FileCode2,
  FileCog,
  FileCog as FileCog2,
  FileDiff,
  FileDigit,
  FileDown,
  FilePen as FileEdit,
  FileHeart,
  FileImage,
  FileInput,
  FileJson,
  FileJson2,
  FileKey,
  FileKey2,
  FileChartLine as FileLineChart,
  FileLock,
  FileLock2,
  FileMinus,
  FileMinus2,
  FileMusic,
  FileOutput,
  FilePen,
  FilePenLine,
  FileChartPie as FilePieChart,
  FilePlus,
  FilePlus2,
  FileQuestion,
  FileScan,
  FileSearch,
  FileSearch2,
  FilePenLine as FileSignature,
  FileSliders,
  FileSpreadsheet,
  FileStack,
  FileSymlink,
  FileTerminal,
  FileText,
  FileType,
  FileType2,
  FileUp,
  FileUser,
  FileVideo,
  FileVideo2,
  FileVolume,
  FileVolume2,
  FileWarning,
  FileX,
  FileX2,
  Files,
  Film,
  Funnel as Filter,
  FunnelX as FilterX,
  Fingerprint,
  FireExtinguisher,
  Fish,
  FishOff,
  FishSymbol,
  Flag,
  FlagOff,
  FlagTriangleLeft,
  FlagTriangleRight,
  Flame,
  FlameKindling,
  Flashlight,
  FlashlightOff,
  FlaskConical,
  FlaskConicalOff,
  FlaskRound,
  FlipHorizontal,
  FlipHorizontal2,
  FlipVertical,
  FlipVertical2,
  Flower,
  Flower2,
  Focus,
  FoldHorizontal,
  FoldVertical,
  Folder,
  FolderArchive,
  FolderCheck,
  FolderClock,
  FolderClosed,
  FolderCode,
  FolderCog,
  FolderCog as FolderCog2,
  FolderDot,
  FolderDown,
  FolderPen as FolderEdit,
  FolderGit,
  FolderGit2,
  FolderHeart,
  FolderInput,
  FolderKanban,
  FolderKey,
  FolderLock,
  FolderMinus,
  FolderOpen,
  FolderOpenDot,
  FolderOutput,
  FolderPen,
  FolderPlus,
  FolderRoot,
  FolderSearch,
  FolderSearch2,
  FolderSymlink,
  FolderSync,
  FolderTree,
  FolderUp,
  FolderX,
  Folders,
  Footprints,
  Utensils as ForkKnife,
  UtensilsCrossed as ForkKnifeCrossed,
  Forklift,
  RectangleEllipsis as FormInput,
  Forward,
  Frame,
  Framer,
  Frown,
  Fuel,
  Fullscreen,
  SquareFunction as FunctionSquare,
  Funnel,
  FunnelPlus,
  FunnelX,
  GalleryHorizontal,
  GalleryHorizontalEnd,
  GalleryThumbnails,
  GalleryVertical,
  GalleryVerticalEnd,
  Gamepad,
  Gamepad2,
  ChartNoAxesGantt as GanttChart,
  SquareChartGantt as GanttChartSquare,
  Gauge,
  CircleGauge as GaugeCircle,
  Gavel,
  Gem,
  Ghost,
  Gift,
  GitBranch,
  GitBranchPlus,
  GitCommitHorizontal as GitCommit,
  GitCommitHorizontal,
  GitCommitVertical,
  GitCompare,
  GitCompareArrows,
  GitFork,
  GitGraph,
  GitMerge,
  GitPullRequest,
  GitPullRequestArrow,
  GitPullRequestClosed,
  GitPullRequestCreate,
  GitPullRequestCreateArrow,
  GitPullRequestDraft,
  Github,
  Gitlab,
  GlassWater,
  Glasses,
  Globe,
  Earth as Globe2,
  GlobeLock,
  Goal,
  Grab,
  GraduationCap,
  Grape,
  Grid3x3 as Grid,
  Grid2x2 as Grid2X2,
  Grid2x2Check as Grid2X2Check,
  Grid2x2Plus as Grid2X2Plus,
  Grid2x2X as Grid2X2X,
  Grid2x2,
  Grid2x2Check,
  Grid2x2Plus,
  Grid2x2X,
  Grid3x3 as Grid3X3,
  Grid3x3,
  Grip,
  GripHorizontal,
  GripVertical,
  Group,
  Guitar,
  Ham,
  Hamburger,
  Hammer,
  Hand,
  HandCoins,
  HandHeart,
  HandHelping,
  HandMetal,
  HandPlatter,
  Handshake,
  HardDrive,
  HardDriveDownload,
  HardDriveUpload,
  HardHat,
  Hash,
  Haze,
  HdmiPort,
  Heading,
  Heading1,
  Heading2,
  Heading3,
  Heading4,
  Heading5,
  Heading6,
  HeadphoneOff,
  Headphones,
  Headset,
  Heart,
  HeartCrack,
  HeartHandshake,
  HeartMinus,
  HeartOff,
  HeartPlus,
  HeartPulse,
  Heater,
  CircleHelp as HelpCircle,
  HandHelping as HelpingHand,
  Hexagon,
  Highlighter,
  History,
  House as Home,
  Hop,
  HopOff,
  Hospital,
  Hotel,
  Hourglass,
  House,
  HousePlug,
  HousePlus,
  HouseWifi,
  IceCreamCone as IceCream,
  IceCreamBowl as IceCream2,
  IceCreamBowl,
  IceCreamCone,
  IdCard,
  Image,
  ImageDown,
  ImageMinus,
  ImageOff,
  ImagePlay,
  ImagePlus,
  ImageUp,
  ImageUpscale,
  Images,
  Import,
  Inbox,
  IndentIncrease as Indent,
  IndentDecrease,
  IndentIncrease,
  IndianRupee,
  Infinity,
  Info,
  SquareMousePointer as Inspect,
  InspectionPanel,
  Instagram,
  Italic,
  IterationCcw,
  IterationCw,
  JapaneseYen,
  Joystick,
  Kanban,
  SquareKanban as KanbanSquare,
  SquareDashedKanban as KanbanSquareDashed,
  Key,
  KeyRound,
  KeySquare,
  Keyboard,
  KeyboardMusic,
  KeyboardOff,
  Lamp,
  LampCeiling,
  LampDesk,
  LampFloor,
  LampWallDown,
  LampWallUp,
  LandPlot,
  Landmark,
  Languages,
  Laptop,
  LaptopMinimal as Laptop2,
  LaptopMinimal,
  LaptopMinimalCheck,
  Lasso,
  LassoSelect,
  Laugh,
  Layers,
  Layers2,
  Layers as Layers3,
  PanelsTopLeft as Layout,
  LayoutDashboard,
  LayoutGrid,
  LayoutList,
  LayoutPanelLeft,
  LayoutPanelTop,
  LayoutTemplate,
  Leaf,
  LeafyGreen,
  Lectern,
  LetterText,
  Library,
  LibraryBig,
  SquareLibrary as LibrarySquare,
  LifeBuoy,
  Ligature,
  Lightbulb,
  LightbulbOff,
  ChartLine as LineChart,
  Link,
  Link2,
  Link2Off,
  Linkedin,
  List,
  ListCheck,
  ListChecks,
  ListCollapse,
  ListEnd,
  ListFilter,
  ListFilterPlus,
  ListMinus,
  ListMusic,
  ListOrdered,
  ListPlus,
  ListRestart,
  ListStart,
  ListTodo,
  ListTree,
  ListVideo,
  ListX,
  Loader,
  LoaderCircle as Loader2,
  LoaderCircle,
  LoaderPinwheel,
  Locate,
  LocateFixed,
  LocateOff,
  LocationEdit,
  Lock,
  LockKeyhole,
  LockKeyholeOpen,
  LockOpen,
  LogIn,
  LogOut,
  Logs,
  Lollipop,
  Luggage,
  SquareM as MSquare,
  Magnet,
  Mail,
  MailCheck,
  MailMinus,
  MailOpen,
  MailPlus,
  MailQuestion,
  MailSearch,
  MailWarning,
  MailX,
  Mailbox,
  Mails,
  Map,
  MapPin,
  MapPinCheck,
  MapPinCheckInside,
  MapPinHouse,
  MapPinMinus,
  MapPinMinusInside,
  MapPinOff,
  MapPinPlus,
  MapPinPlusInside,
  MapPinX,
  MapPinXInside,
  MapPinned,
  MapPlus,
  Mars,
  MarsStroke,
  Martini,
  Maximize,
  Maximize2,
  Medal,
  Megaphone,
  MegaphoneOff,
  Meh,
  MemoryStick,
  Menu,
  SquareMenu as MenuSquare,
  Merge,
  MessageCircle,
  MessageCircleCode,
  MessageCircleDashed,
  MessageCircleHeart,
  MessageCircleMore,
  MessageCircleOff,
  MessageCirclePlus,
  MessageCircleQuestion,
  MessageCircleReply,
  MessageCircleWarning,
  MessageCircleX,
  MessageSquare,
  MessageSquareCode,
  MessageSquareDashed,
  MessageSquareDiff,
  MessageSquareDot,
  MessageSquareHeart,
  MessageSquareLock,
  MessageSquareMore,
  MessageSquareOff,
  MessageSquarePlus,
  MessageSquareQuote,
  MessageSquareReply,
  MessageSquareShare,
  MessageSquareText,
  MessageSquareWarning,
  MessageSquareX,
  MessagesSquare,
  Mic,
  MicVocal as Mic2,
  MicOff,
  MicVocal,
  Microchip,
  Microscope,
  Microwave,
  Milestone,
  Milk,
  MilkOff,
  Minimize,
  Minimize2,
  Minus,
  CircleMinus as MinusCircle,
  SquareMinus as MinusSquare,
  Monitor,
  MonitorCheck,
  MonitorCog,
  MonitorDot,
  MonitorDown,
  MonitorOff,
  MonitorPause,
  MonitorPlay,
  MonitorSmartphone,
  MonitorSpeaker,
  MonitorStop,
  MonitorUp,
  MonitorX,
  Moon,
  MoonStar,
  Ellipsis as MoreHorizontal,
  EllipsisVertical as MoreVertical,
  Mountain,
  MountainSnow,
  Mouse,
  MouseOff,
  MousePointer,
  MousePointer2,
  MousePointerBan,
  MousePointerClick,
  SquareDashedMousePointer as MousePointerSquareDashed,
  Move,
  Move3d as Move3D,
  Move3d,
  MoveDiagonal,
  MoveDiagonal2,
  MoveDown,
  MoveDownLeft,
  MoveDownRight,
  MoveHorizontal,
  MoveLeft,
  MoveRight,
  MoveUp,
  MoveUpLeft,
  MoveUpRight,
  MoveVertical,
  Music,
  Music2,
  Music3,
  Music4,
  Navigation,
  Navigation2,
  Navigation2Off,
  NavigationOff,
  Network,
  Newspaper,
  Nfc,
  NonBinary,
  Notebook,
  NotebookPen,
  NotebookTabs,
  NotebookText,
  NotepadText,
  NotepadTextDashed,
  Nut,
  NutOff,
  Octagon,
  OctagonAlert,
  OctagonMinus,
  OctagonPause,
  OctagonX,
  Omega,
  Option,
  Orbit,
  Origami,
  IndentDecrease as Outdent,
  Package,
  Package2,
  PackageCheck,
  PackageMinus,
  PackageOpen,
  PackagePlus,
  PackageSearch,
  PackageX,
  PaintBucket,
  PaintRoller,
  Paintbrush,
  PaintbrushVertical as Paintbrush2,
  PaintbrushVertical,
  Palette,
  TreePalm as Palmtree,
  PanelBottom,
  PanelBottomClose,
  PanelBottomDashed,
  PanelBottomDashed as PanelBottomInactive,
  PanelBottomOpen,
  PanelLeft,
  PanelLeftClose,
  PanelLeftDashed,
  PanelLeftDashed as PanelLeftInactive,
  PanelLeftOpen,
  PanelRight,
  PanelRightClose,
  PanelRightDashed,
  PanelRightDashed as PanelRightInactive,
  PanelRightOpen,
  PanelTop,
  PanelTopClose,
  PanelTopDashed,
  PanelTopDashed as PanelTopInactive,
  PanelTopOpen,
  PanelsLeftBottom,
  Columns3 as PanelsLeftRight,
  PanelsRightBottom,
  Rows3 as PanelsTopBottom,
  PanelsTopLeft,
  Paperclip,
  Parentheses,
  CircleParking as ParkingCircle,
  CircleParkingOff as ParkingCircleOff,
  ParkingMeter,
  SquareParking as ParkingSquare,
  SquareParkingOff as ParkingSquareOff,
  PartyPopper,
  Pause,
  CirclePause as PauseCircle,
  OctagonPause as PauseOctagon,
  PawPrint,
  PcCase,
  Pen,
  SquarePen as PenBox,
  PenLine,
  PenOff,
  SquarePen as PenSquare,
  PenTool,
  Pencil,
  PencilLine,
  PencilOff,
  PencilRuler,
  Pentagon,
  Percent,
  CirclePercent as PercentCircle,
  DiamondPercent as PercentDiamond,
  SquarePercent as PercentSquare,
  PersonStanding,
  PhilippinePeso,
  Phone,
  PhoneCall,
  PhoneForwarded,
  PhoneIncoming,
  PhoneMissed,
  PhoneOff,
  PhoneOutgoing,
  Pi,
  SquarePi as PiSquare,
  Piano,
  Pickaxe,
  PictureInPicture,
  PictureInPicture2,
  ChartPie as PieChart,
  PiggyBank,
  Pilcrow,
  PilcrowLeft,
  PilcrowRight,
  SquarePilcrow as PilcrowSquare,
  Pill,
  PillBottle,
  Pin,
  PinOff,
  Pipette,
  Pizza,
  Plane,
  PlaneLanding,
  PlaneTakeoff,
  Play,
  CirclePlay as PlayCircle,
  SquarePlay as PlaySquare,
  Plug,
  Plug2,
  PlugZap,
  PlugZap as PlugZap2,
  Plus,
  CirclePlus as PlusCircle,
  SquarePlus as PlusSquare,
  Pocket,
  PocketKnife,
  Podcast,
  Pointer,
  PointerOff,
  Popcorn,
  Popsicle,
  PoundSterling,
  Power,
  CirclePower as PowerCircle,
  PowerOff,
  SquarePower as PowerSquare,
  Presentation,
  Printer,
  PrinterCheck,
  Projector,
  Proportions,
  Puzzle,
  Pyramid,
  QrCode,
  Quote,
  Rabbit,
  Radar,
  Radiation,
  Radical,
  Radio,
  RadioReceiver,
  RadioTower,
  Radius,
  RailSymbol,
  Rainbow,
  Rat,
  Ratio,
  Receipt,
  ReceiptCent,
  ReceiptEuro,
  ReceiptIndianRupee,
  ReceiptJapaneseYen,
  ReceiptPoundSterling,
  ReceiptRussianRuble,
  ReceiptSwissFranc,
  ReceiptText,
  RectangleEllipsis,
  RectangleGoggles,
  RectangleHorizontal,
  RectangleVertical,
  Recycle,
  Redo,
  Redo2,
  RedoDot,
  RefreshCcw,
  RefreshCcwDot,
  RefreshCw,
  RefreshCwOff,
  Refrigerator,
  Regex,
  RemoveFormatting,
  Repeat,
  Repeat1,
  Repeat2,
  Replace,
  ReplaceAll,
  Reply,
  ReplyAll,
  Rewind,
  Ribbon,
  Rocket,
  RockingChair,
  RollerCoaster,
  Rotate3d as Rotate3D,
  Rotate3d,
  RotateCcw,
  RotateCcwKey,
  RotateCcwSquare,
  RotateCw,
  RotateCwSquare,
  Route,
  RouteOff,
  Router,
  Rows2 as Rows,
  Rows2,
  Rows3,
  Rows4,
  Rss,
  Ruler,
  RulerDimensionLine,
  RussianRuble,
  Sailboat,
  Salad,
  Sandwich,
  Satellite,
  SatelliteDish,
  SaudiRiyal,
  Save,
  SaveAll,
  SaveOff,
  Scale,
  Scale3d as Scale3D,
  Scale3d,
  Scaling,
  Scan,
  ScanBarcode,
  ScanEye,
  ScanFace,
  ScanHeart,
  ScanLine,
  ScanQrCode,
  ScanSearch,
  ScanText,
  ChartScatter as ScatterChart,
  School,
  University as School2,
  Scissors,
  ScissorsLineDashed,
  SquareScissors as ScissorsSquare,
  SquareBottomDashedScissors as ScissorsSquareDashedBottom,
  ScreenShare,
  ScreenShareOff,
  Scroll,
  ScrollText,
  Search,
  SearchCheck,
  SearchCode,
  SearchSlash,
  SearchX,
  Section,
  Send,
  SendHorizontal as SendHorizonal,
  SendHorizontal,
  SendToBack,
  SeparatorHorizontal,
  SeparatorVertical,
  Server,
  ServerCog,
  ServerCrash,
  ServerOff,
  Settings,
  Settings2,
  Shapes,
  Share,
  Share2,
  Sheet,
  Shell,
  Shield,
  ShieldAlert,
  ShieldBan,
  ShieldCheck,
  ShieldX as ShieldClose,
  ShieldEllipsis,
  ShieldHalf,
  ShieldMinus,
  ShieldOff,
  ShieldPlus,
  ShieldQuestion,
  ShieldUser,
  ShieldX,
  Ship,
  ShipWheel,
  Shirt,
  ShoppingBag,
  ShoppingBasket,
  ShoppingCart,
  Shovel,
  ShowerHead,
  Shredder,
  Shrimp,
  Shrink,
  Shrub,
  Shuffle,
  PanelLeft as Sidebar,
  PanelLeftClose as SidebarClose,
  PanelLeftOpen as SidebarOpen,
  Sigma,
  SquareSigma as SigmaSquare,
  Signal,
  SignalHigh,
  SignalLow,
  SignalMedium,
  SignalZero,
  Signature,
  Signpost,
  SignpostBig,
  Siren,
  SkipBack,
  SkipForward,
  Skull,
  Slack,
  Slash,
  SquareSlash as SlashSquare,
  Slice,
  SlidersVertical as Sliders,
  SlidersHorizontal,
  SlidersVertical,
  Smartphone,
  SmartphoneCharging,
  SmartphoneNfc,
  Smile,
  SmilePlus,
  Snail,
  Snowflake,
  SoapDispenserDroplet,
  Sofa,
  ArrowUpNarrowWide as SortAsc,
  ArrowDownWideNarrow as SortDesc,
  Soup,
  Space,
  Spade,
  Sparkle,
  Sparkles,
  Speaker,
  Speech,
  SpellCheck,
  SpellCheck2,
  Spline,
  SplinePointer,
  Split,
  SquareSplitHorizontal as SplitSquareHorizontal,
  SquareSplitVertical as SplitSquareVertical,
  SprayCan,
  Sprout,
  Square,
  SquareActivity,
  SquareArrowDown,
  SquareArrowDownLeft,
  SquareArrowDownRight,
  SquareArrowLeft,
  SquareArrowOutDownLeft,
  SquareArrowOutDownRight,
  SquareArrowOutUpLeft,
  SquareArrowOutUpRight,
  SquareArrowRight,
  SquareArrowUp,
  SquareArrowUpLeft,
  SquareArrowUpRight,
  SquareAsterisk,
  SquareBottomDashedScissors,
  SquareChartGantt,
  SquareCheck,
  SquareCheckBig,
  SquareChevronDown,
  SquareChevronLeft,
  SquareChevronRight,
  SquareChevronUp,
  SquareCode,
  SquareDashed,
  SquareDashedBottom,
  SquareDashedBottomCode,
  SquareDashedKanban,
  SquareDashedMousePointer,
  SquareDivide,
  SquareDot,
  SquareEqual,
  SquareFunction,
  SquareChartGantt as SquareGanttChart,
  SquareKanban,
  SquareLibrary,
  SquareM,
  SquareMenu,
  SquareMinus,
  SquareMousePointer,
  SquareParking,
  SquareParkingOff,
  SquarePen,
  SquarePercent,
  SquarePi,
  SquarePilcrow,
  SquarePlay,
  SquarePlus,
  SquarePower,
  SquareRadical,
  SquareRoundCorner,
  SquareScissors,
  SquareSigma,
  SquareSlash,
  SquareSplitHorizontal,
  SquareSplitVertical,
  SquareSquare,
  SquareStack,
  SquareTerminal,
  SquareUser,
  SquareUserRound,
  SquareX,
  SquaresExclude,
  SquaresIntersect,
  SquaresSubtract,
  SquaresUnite,
  Squircle,
  Squirrel,
  Stamp,
  Star,
  StarHalf,
  StarOff,
  Sparkles as Stars,
  StepBack,
  StepForward,
  Stethoscope,
  Sticker,
  StickyNote,
  CircleStop as StopCircle,
  Store,
  StretchHorizontal,
  StretchVertical,
  Strikethrough,
  Subscript,
  Captions as Subtitles,
  Sun,
  SunDim,
  SunMedium,
  SunMoon,
  SunSnow,
  Sunrise,
  Sunset,
  Superscript,
  SwatchBook,
  SwissFranc,
  SwitchCamera,
  Sword,
  Swords,
  Syringe,
  Table,
  Table2,
  TableCellsMerge,
  TableCellsSplit,
  TableColumnsSplit,
  Columns3Cog as TableConfig,
  TableOfContents,
  TableProperties,
  TableRowsSplit,
  Tablet,
  TabletSmartphone,
  Tablets,
  Tag,
  Tags,
  Tally1,
  Tally2,
  Tally3,
  Tally4,
  Tally5,
  Tangent,
  Target,
  Telescope,
  Tent,
  TentTree,
  Terminal,
  SquareTerminal as TerminalSquare,
  TestTube,
  TestTubeDiagonal as TestTube2,
  TestTubeDiagonal,
  TestTubes,
  Text,
  TextCursor,
  TextCursorInput,
  TextQuote,
  TextSearch,
  TextSelect,
  TextSelect as TextSelection,
  Theater,
  Thermometer,
  ThermometerSnowflake,
  ThermometerSun,
  ThumbsDown,
  ThumbsUp,
  Ticket,
  TicketCheck,
  TicketMinus,
  TicketPercent,
  TicketPlus,
  TicketSlash,
  TicketX,
  Tickets,
  TicketsPlane,
  Timer,
  TimerOff,
  TimerReset,
  ToggleLeft,
  ToggleRight,
  Toilet,
  Tornado,
  Torus,
  Touchpad,
  TouchpadOff,
  TowerControl,
  ToyBrick,
  Tractor,
  TrafficCone,
  TramFront as Train,
  TrainFront,
  TrainFrontTunnel,
  TrainTrack,
  TramFront,
  Transgender,
  Trash,
  Trash2,
  TreeDeciduous,
  TreePalm,
  TreePine,
  Trees,
  Trello,
  TrendingDown,
  TrendingUp,
  TrendingUpDown,
  Triangle,
  TriangleAlert,
  TriangleDashed,
  TriangleRight,
  Trophy,
  Truck,
  TruckElectric,
  Turtle,
  Tv,
  TvMinimal as Tv2,
  TvMinimal,
  TvMinimalPlay,
  Twitch,
  Twitter,
  Type,
  TypeOutline,
  Umbrella,
  UmbrellaOff,
  Underline,
  Undo,
  Undo2,
  UndoDot,
  UnfoldHorizontal,
  UnfoldVertical,
  Ungroup,
  University,
  Unlink,
  Unlink2,
  LockOpen as Unlock,
  LockKeyholeOpen as UnlockKeyhole,
  Unplug,
  Upload,
  CloudUpload as UploadCloud,
  Usb,
  User,
  UserRound as User2,
  UserCheck,
  UserRoundCheck as UserCheck2,
  CircleUser as UserCircle,
  CircleUserRound as UserCircle2,
  UserCog,
  UserRoundCog as UserCog2,
  UserLock,
  UserMinus,
  UserRoundMinus as UserMinus2,
  UserPen,
  UserPlus,
  UserRoundPlus as UserPlus2,
  UserRound,
  UserRoundCheck,
  UserRoundCog,
  UserRoundMinus,
  UserRoundPen,
  UserRoundPlus,
  UserRoundSearch,
  UserRoundX,
  UserSearch,
  SquareUser as UserSquare,
  SquareUserRound as UserSquare2,
  UserX,
  UserRoundX as UserX2,
  Users,
  UsersRound as Users2,
  UsersRound,
  Utensils,
  UtensilsCrossed,
  UtilityPole,
  Variable,
  Vault,
  Vegan,
  VenetianMask,
  Venus,
  VenusAndMars,
  BadgeCheck as Verified,
  Vibrate,
  VibrateOff,
  Video,
  VideoOff,
  Videotape,
  View,
  Voicemail,
  Volleyball,
  Volume,
  Volume1,
  Volume2,
  VolumeOff,
  VolumeX,
  Vote,
  Wallet,
  WalletMinimal as Wallet2,
  WalletCards,
  WalletMinimal,
  Wallpaper,
  Wand,
  WandSparkles as Wand2,
  WandSparkles,
  Warehouse,
  WashingMachine,
  Watch,
  Waves,
  WavesLadder,
  Waypoints,
  Webcam,
  Webhook,
  WebhookOff,
  Weight,
  Wheat,
  WheatOff,
  WholeWord,
  Wifi,
  WifiHigh,
  WifiLow,
  WifiOff,
  WifiPen,
  WifiZero,
  Wind,
  WindArrowDown,
  Wine,
  WineOff,
  Workflow,
  Worm,
  WrapText,
  Wrench,
  X,
  CircleX as XCircle,
  OctagonX as XOctagon,
  SquareX as XSquare,
  Youtube,
  Zap,
  ZapOff,
  ZoomIn,
  ZoomOut,
  createElement,
  createIcons,
  iconsAndAliases_exports as icons
};
/*! Bundled license information:

lucide/dist/esm/defaultAttributes.js:
lucide/dist/esm/createElement.js:
lucide/dist/esm/replaceElement.js:
lucide/dist/esm/icons/a-arrow-down.js:
lucide/dist/esm/icons/a-arrow-up.js:
lucide/dist/esm/icons/a-large-small.js:
lucide/dist/esm/icons/accessibility.js:
lucide/dist/esm/icons/activity.js:
lucide/dist/esm/icons/air-vent.js:
lucide/dist/esm/icons/airplay.js:
lucide/dist/esm/icons/alarm-clock-check.js:
lucide/dist/esm/icons/alarm-clock-minus.js:
lucide/dist/esm/icons/alarm-clock-off.js:
lucide/dist/esm/icons/alarm-clock-plus.js:
lucide/dist/esm/icons/alarm-clock.js:
lucide/dist/esm/icons/alarm-smoke.js:
lucide/dist/esm/icons/album.js:
lucide/dist/esm/icons/align-center-vertical.js:
lucide/dist/esm/icons/align-center-horizontal.js:
lucide/dist/esm/icons/align-center.js:
lucide/dist/esm/icons/align-end-horizontal.js:
lucide/dist/esm/icons/align-end-vertical.js:
lucide/dist/esm/icons/align-horizontal-distribute-center.js:
lucide/dist/esm/icons/align-horizontal-distribute-end.js:
lucide/dist/esm/icons/align-horizontal-distribute-start.js:
lucide/dist/esm/icons/align-horizontal-justify-center.js:
lucide/dist/esm/icons/align-horizontal-justify-end.js:
lucide/dist/esm/icons/align-horizontal-justify-start.js:
lucide/dist/esm/icons/align-horizontal-space-around.js:
lucide/dist/esm/icons/align-horizontal-space-between.js:
lucide/dist/esm/icons/align-justify.js:
lucide/dist/esm/icons/align-left.js:
lucide/dist/esm/icons/align-right.js:
lucide/dist/esm/icons/align-start-horizontal.js:
lucide/dist/esm/icons/align-start-vertical.js:
lucide/dist/esm/icons/align-vertical-distribute-center.js:
lucide/dist/esm/icons/align-vertical-distribute-end.js:
lucide/dist/esm/icons/align-vertical-distribute-start.js:
lucide/dist/esm/icons/align-vertical-justify-center.js:
lucide/dist/esm/icons/align-vertical-justify-end.js:
lucide/dist/esm/icons/align-vertical-justify-start.js:
lucide/dist/esm/icons/align-vertical-space-around.js:
lucide/dist/esm/icons/align-vertical-space-between.js:
lucide/dist/esm/icons/ambulance.js:
lucide/dist/esm/icons/ampersand.js:
lucide/dist/esm/icons/ampersands.js:
lucide/dist/esm/icons/amphora.js:
lucide/dist/esm/icons/anchor.js:
lucide/dist/esm/icons/angry.js:
lucide/dist/esm/icons/antenna.js:
lucide/dist/esm/icons/annoyed.js:
lucide/dist/esm/icons/anvil.js:
lucide/dist/esm/icons/aperture.js:
lucide/dist/esm/icons/app-window-mac.js:
lucide/dist/esm/icons/app-window.js:
lucide/dist/esm/icons/apple.js:
lucide/dist/esm/icons/archive-restore.js:
lucide/dist/esm/icons/archive-x.js:
lucide/dist/esm/icons/archive.js:
lucide/dist/esm/icons/armchair.js:
lucide/dist/esm/icons/arrow-big-down-dash.js:
lucide/dist/esm/icons/arrow-big-down.js:
lucide/dist/esm/icons/arrow-big-left-dash.js:
lucide/dist/esm/icons/arrow-big-left.js:
lucide/dist/esm/icons/arrow-big-right-dash.js:
lucide/dist/esm/icons/arrow-big-up-dash.js:
lucide/dist/esm/icons/arrow-big-right.js:
lucide/dist/esm/icons/arrow-big-up.js:
lucide/dist/esm/icons/arrow-down-0-1.js:
lucide/dist/esm/icons/arrow-down-1-0.js:
lucide/dist/esm/icons/arrow-down-a-z.js:
lucide/dist/esm/icons/arrow-down-from-line.js:
lucide/dist/esm/icons/arrow-down-left.js:
lucide/dist/esm/icons/arrow-down-right.js:
lucide/dist/esm/icons/arrow-down-narrow-wide.js:
lucide/dist/esm/icons/arrow-down-to-dot.js:
lucide/dist/esm/icons/arrow-down-to-line.js:
lucide/dist/esm/icons/arrow-down-up.js:
lucide/dist/esm/icons/arrow-down-wide-narrow.js:
lucide/dist/esm/icons/arrow-down-z-a.js:
lucide/dist/esm/icons/arrow-down.js:
lucide/dist/esm/icons/arrow-left-from-line.js:
lucide/dist/esm/icons/arrow-left-right.js:
lucide/dist/esm/icons/arrow-left-to-line.js:
lucide/dist/esm/icons/arrow-left.js:
lucide/dist/esm/icons/arrow-right-from-line.js:
lucide/dist/esm/icons/arrow-right-left.js:
lucide/dist/esm/icons/arrow-right-to-line.js:
lucide/dist/esm/icons/arrow-right.js:
lucide/dist/esm/icons/arrow-up-0-1.js:
lucide/dist/esm/icons/arrow-up-1-0.js:
lucide/dist/esm/icons/arrow-up-a-z.js:
lucide/dist/esm/icons/arrow-up-down.js:
lucide/dist/esm/icons/arrow-up-from-dot.js:
lucide/dist/esm/icons/arrow-up-from-line.js:
lucide/dist/esm/icons/arrow-up-left.js:
lucide/dist/esm/icons/arrow-up-narrow-wide.js:
lucide/dist/esm/icons/arrow-up-right.js:
lucide/dist/esm/icons/arrow-up-to-line.js:
lucide/dist/esm/icons/arrow-up-wide-narrow.js:
lucide/dist/esm/icons/arrow-up-z-a.js:
lucide/dist/esm/icons/arrows-up-from-line.js:
lucide/dist/esm/icons/arrow-up.js:
lucide/dist/esm/icons/asterisk.js:
lucide/dist/esm/icons/at-sign.js:
lucide/dist/esm/icons/atom.js:
lucide/dist/esm/icons/audio-lines.js:
lucide/dist/esm/icons/audio-waveform.js:
lucide/dist/esm/icons/award.js:
lucide/dist/esm/icons/axe.js:
lucide/dist/esm/icons/axis-3d.js:
lucide/dist/esm/icons/baby.js:
lucide/dist/esm/icons/backpack.js:
lucide/dist/esm/icons/badge-alert.js:
lucide/dist/esm/icons/badge-cent.js:
lucide/dist/esm/icons/badge-check.js:
lucide/dist/esm/icons/badge-dollar-sign.js:
lucide/dist/esm/icons/badge-euro.js:
lucide/dist/esm/icons/badge-help.js:
lucide/dist/esm/icons/badge-indian-rupee.js:
lucide/dist/esm/icons/badge-info.js:
lucide/dist/esm/icons/badge-japanese-yen.js:
lucide/dist/esm/icons/badge-minus.js:
lucide/dist/esm/icons/badge-percent.js:
lucide/dist/esm/icons/badge-plus.js:
lucide/dist/esm/icons/badge-pound-sterling.js:
lucide/dist/esm/icons/badge-russian-ruble.js:
lucide/dist/esm/icons/badge-x.js:
lucide/dist/esm/icons/badge-swiss-franc.js:
lucide/dist/esm/icons/badge.js:
lucide/dist/esm/icons/baggage-claim.js:
lucide/dist/esm/icons/ban.js:
lucide/dist/esm/icons/banana.js:
lucide/dist/esm/icons/bandage.js:
lucide/dist/esm/icons/banknote-arrow-down.js:
lucide/dist/esm/icons/banknote-arrow-up.js:
lucide/dist/esm/icons/banknote-x.js:
lucide/dist/esm/icons/banknote.js:
lucide/dist/esm/icons/barcode.js:
lucide/dist/esm/icons/baseline.js:
lucide/dist/esm/icons/bath.js:
lucide/dist/esm/icons/battery-charging.js:
lucide/dist/esm/icons/battery-low.js:
lucide/dist/esm/icons/battery-full.js:
lucide/dist/esm/icons/battery-medium.js:
lucide/dist/esm/icons/battery-plus.js:
lucide/dist/esm/icons/battery-warning.js:
lucide/dist/esm/icons/battery.js:
lucide/dist/esm/icons/beaker.js:
lucide/dist/esm/icons/bean-off.js:
lucide/dist/esm/icons/bean.js:
lucide/dist/esm/icons/bed-double.js:
lucide/dist/esm/icons/bed-single.js:
lucide/dist/esm/icons/bed.js:
lucide/dist/esm/icons/beef.js:
lucide/dist/esm/icons/beer-off.js:
lucide/dist/esm/icons/beer.js:
lucide/dist/esm/icons/bell-dot.js:
lucide/dist/esm/icons/bell-electric.js:
lucide/dist/esm/icons/bell-minus.js:
lucide/dist/esm/icons/bell-plus.js:
lucide/dist/esm/icons/bell-off.js:
lucide/dist/esm/icons/bell.js:
lucide/dist/esm/icons/bell-ring.js:
lucide/dist/esm/icons/between-horizontal-end.js:
lucide/dist/esm/icons/between-horizontal-start.js:
lucide/dist/esm/icons/between-vertical-end.js:
lucide/dist/esm/icons/between-vertical-start.js:
lucide/dist/esm/icons/biceps-flexed.js:
lucide/dist/esm/icons/bike.js:
lucide/dist/esm/icons/binoculars.js:
lucide/dist/esm/icons/binary.js:
lucide/dist/esm/icons/biohazard.js:
lucide/dist/esm/icons/bird.js:
lucide/dist/esm/icons/bitcoin.js:
lucide/dist/esm/icons/blend.js:
lucide/dist/esm/icons/blinds.js:
lucide/dist/esm/icons/blocks.js:
lucide/dist/esm/icons/bluetooth-connected.js:
lucide/dist/esm/icons/bluetooth-off.js:
lucide/dist/esm/icons/bluetooth-searching.js:
lucide/dist/esm/icons/bold.js:
lucide/dist/esm/icons/bolt.js:
lucide/dist/esm/icons/bluetooth.js:
lucide/dist/esm/icons/bone.js:
lucide/dist/esm/icons/bomb.js:
lucide/dist/esm/icons/book-audio.js:
lucide/dist/esm/icons/book-check.js:
lucide/dist/esm/icons/book-a.js:
lucide/dist/esm/icons/book-copy.js:
lucide/dist/esm/icons/book-down.js:
lucide/dist/esm/icons/book-dashed.js:
lucide/dist/esm/icons/book-headphones.js:
lucide/dist/esm/icons/book-heart.js:
lucide/dist/esm/icons/book-image.js:
lucide/dist/esm/icons/book-key.js:
lucide/dist/esm/icons/book-marked.js:
lucide/dist/esm/icons/book-lock.js:
lucide/dist/esm/icons/book-minus.js:
lucide/dist/esm/icons/book-open-check.js:
lucide/dist/esm/icons/book-open-text.js:
lucide/dist/esm/icons/book-open.js:
lucide/dist/esm/icons/book-plus.js:
lucide/dist/esm/icons/book-text.js:
lucide/dist/esm/icons/book-type.js:
lucide/dist/esm/icons/book-up-2.js:
lucide/dist/esm/icons/book-up.js:
lucide/dist/esm/icons/book-user.js:
lucide/dist/esm/icons/book-x.js:
lucide/dist/esm/icons/book.js:
lucide/dist/esm/icons/bookmark-check.js:
lucide/dist/esm/icons/bookmark-minus.js:
lucide/dist/esm/icons/bookmark-plus.js:
lucide/dist/esm/icons/bookmark-x.js:
lucide/dist/esm/icons/bookmark.js:
lucide/dist/esm/icons/boom-box.js:
lucide/dist/esm/icons/bot-message-square.js:
lucide/dist/esm/icons/bot-off.js:
lucide/dist/esm/icons/bot.js:
lucide/dist/esm/icons/bow-arrow.js:
lucide/dist/esm/icons/box.js:
lucide/dist/esm/icons/boxes.js:
lucide/dist/esm/icons/braces.js:
lucide/dist/esm/icons/brackets.js:
lucide/dist/esm/icons/brain-cog.js:
lucide/dist/esm/icons/brain-circuit.js:
lucide/dist/esm/icons/brain.js:
lucide/dist/esm/icons/brick-wall-fire.js:
lucide/dist/esm/icons/brick-wall.js:
lucide/dist/esm/icons/briefcase-business.js:
lucide/dist/esm/icons/briefcase-conveyor-belt.js:
lucide/dist/esm/icons/briefcase-medical.js:
lucide/dist/esm/icons/bring-to-front.js:
lucide/dist/esm/icons/brush-cleaning.js:
lucide/dist/esm/icons/briefcase.js:
lucide/dist/esm/icons/brush.js:
lucide/dist/esm/icons/bug-off.js:
lucide/dist/esm/icons/bug-play.js:
lucide/dist/esm/icons/bubbles.js:
lucide/dist/esm/icons/bug.js:
lucide/dist/esm/icons/building-2.js:
lucide/dist/esm/icons/building.js:
lucide/dist/esm/icons/bus.js:
lucide/dist/esm/icons/cable-car.js:
lucide/dist/esm/icons/bus-front.js:
lucide/dist/esm/icons/cable.js:
lucide/dist/esm/icons/cake-slice.js:
lucide/dist/esm/icons/cake.js:
lucide/dist/esm/icons/calendar-1.js:
lucide/dist/esm/icons/calendar-arrow-down.js:
lucide/dist/esm/icons/calculator.js:
lucide/dist/esm/icons/calendar-arrow-up.js:
lucide/dist/esm/icons/calendar-check-2.js:
lucide/dist/esm/icons/calendar-check.js:
lucide/dist/esm/icons/calendar-clock.js:
lucide/dist/esm/icons/calendar-cog.js:
lucide/dist/esm/icons/calendar-days.js:
lucide/dist/esm/icons/calendar-fold.js:
lucide/dist/esm/icons/calendar-heart.js:
lucide/dist/esm/icons/calendar-minus-2.js:
lucide/dist/esm/icons/calendar-minus.js:
lucide/dist/esm/icons/calendar-off.js:
lucide/dist/esm/icons/calendar-plus-2.js:
lucide/dist/esm/icons/calendar-plus.js:
lucide/dist/esm/icons/calendar-range.js:
lucide/dist/esm/icons/calendar-search.js:
lucide/dist/esm/icons/calendar-sync.js:
lucide/dist/esm/icons/calendar-x-2.js:
lucide/dist/esm/icons/calendar-x.js:
lucide/dist/esm/icons/calendar.js:
lucide/dist/esm/icons/camera-off.js:
lucide/dist/esm/icons/camera.js:
lucide/dist/esm/icons/candy-cane.js:
lucide/dist/esm/icons/candy-off.js:
lucide/dist/esm/icons/candy.js:
lucide/dist/esm/icons/cannabis.js:
lucide/dist/esm/icons/captions-off.js:
lucide/dist/esm/icons/car-front.js:
lucide/dist/esm/icons/captions.js:
lucide/dist/esm/icons/car-taxi-front.js:
lucide/dist/esm/icons/car.js:
lucide/dist/esm/icons/caravan.js:
lucide/dist/esm/icons/carrot.js:
lucide/dist/esm/icons/case-lower.js:
lucide/dist/esm/icons/case-sensitive.js:
lucide/dist/esm/icons/case-upper.js:
lucide/dist/esm/icons/cassette-tape.js:
lucide/dist/esm/icons/cast.js:
lucide/dist/esm/icons/castle.js:
lucide/dist/esm/icons/cat.js:
lucide/dist/esm/icons/chart-area.js:
lucide/dist/esm/icons/cctv.js:
lucide/dist/esm/icons/chart-bar-big.js:
lucide/dist/esm/icons/chart-bar-decreasing.js:
lucide/dist/esm/icons/chart-bar-stacked.js:
lucide/dist/esm/icons/chart-bar-increasing.js:
lucide/dist/esm/icons/chart-bar.js:
lucide/dist/esm/icons/chart-candlestick.js:
lucide/dist/esm/icons/chart-column-big.js:
lucide/dist/esm/icons/chart-column-decreasing.js:
lucide/dist/esm/icons/chart-column-increasing.js:
lucide/dist/esm/icons/chart-column-stacked.js:
lucide/dist/esm/icons/chart-column.js:
lucide/dist/esm/icons/chart-gantt.js:
lucide/dist/esm/icons/chart-line.js:
lucide/dist/esm/icons/chart-network.js:
lucide/dist/esm/icons/chart-no-axes-column-decreasing.js:
lucide/dist/esm/icons/chart-no-axes-column-increasing.js:
lucide/dist/esm/icons/chart-no-axes-column.js:
lucide/dist/esm/icons/chart-no-axes-combined.js:
lucide/dist/esm/icons/chart-no-axes-gantt.js:
lucide/dist/esm/icons/chart-pie.js:
lucide/dist/esm/icons/chart-scatter.js:
lucide/dist/esm/icons/chart-spline.js:
lucide/dist/esm/icons/check-check.js:
lucide/dist/esm/icons/check.js:
lucide/dist/esm/icons/chef-hat.js:
lucide/dist/esm/icons/cherry.js:
lucide/dist/esm/icons/chevron-down.js:
lucide/dist/esm/icons/chevron-first.js:
lucide/dist/esm/icons/chevron-left.js:
lucide/dist/esm/icons/chevron-last.js:
lucide/dist/esm/icons/chevron-right.js:
lucide/dist/esm/icons/chevrons-down-up.js:
lucide/dist/esm/icons/chevron-up.js:
lucide/dist/esm/icons/chevrons-down.js:
lucide/dist/esm/icons/chevrons-left-right-ellipsis.js:
lucide/dist/esm/icons/chevrons-left-right.js:
lucide/dist/esm/icons/chevrons-left.js:
lucide/dist/esm/icons/chevrons-right-left.js:
lucide/dist/esm/icons/chevrons-up-down.js:
lucide/dist/esm/icons/chevrons-right.js:
lucide/dist/esm/icons/chevrons-up.js:
lucide/dist/esm/icons/chrome.js:
lucide/dist/esm/icons/cigarette-off.js:
lucide/dist/esm/icons/church.js:
lucide/dist/esm/icons/cigarette.js:
lucide/dist/esm/icons/circle-alert.js:
lucide/dist/esm/icons/circle-arrow-down.js:
lucide/dist/esm/icons/circle-arrow-left.js:
lucide/dist/esm/icons/circle-arrow-out-down-left.js:
lucide/dist/esm/icons/circle-arrow-out-down-right.js:
lucide/dist/esm/icons/circle-arrow-out-up-left.js:
lucide/dist/esm/icons/circle-arrow-out-up-right.js:
lucide/dist/esm/icons/circle-arrow-up.js:
lucide/dist/esm/icons/circle-arrow-right.js:
lucide/dist/esm/icons/circle-check-big.js:
lucide/dist/esm/icons/circle-check.js:
lucide/dist/esm/icons/circle-chevron-down.js:
lucide/dist/esm/icons/circle-chevron-left.js:
lucide/dist/esm/icons/circle-chevron-right.js:
lucide/dist/esm/icons/circle-chevron-up.js:
lucide/dist/esm/icons/circle-dashed.js:
lucide/dist/esm/icons/circle-divide.js:
lucide/dist/esm/icons/circle-dollar-sign.js:
lucide/dist/esm/icons/circle-dot-dashed.js:
lucide/dist/esm/icons/circle-dot.js:
lucide/dist/esm/icons/circle-ellipsis.js:
lucide/dist/esm/icons/circle-equal.js:
lucide/dist/esm/icons/circle-fading-arrow-up.js:
lucide/dist/esm/icons/circle-fading-plus.js:
lucide/dist/esm/icons/circle-gauge.js:
lucide/dist/esm/icons/circle-help.js:
lucide/dist/esm/icons/circle-minus.js:
lucide/dist/esm/icons/circle-off.js:
lucide/dist/esm/icons/circle-parking-off.js:
lucide/dist/esm/icons/circle-parking.js:
lucide/dist/esm/icons/circle-pause.js:
lucide/dist/esm/icons/circle-percent.js:
lucide/dist/esm/icons/circle-play.js:
lucide/dist/esm/icons/circle-plus.js:
lucide/dist/esm/icons/circle-power.js:
lucide/dist/esm/icons/circle-slash-2.js:
lucide/dist/esm/icons/circle-slash.js:
lucide/dist/esm/icons/circle-small.js:
lucide/dist/esm/icons/circle-stop.js:
lucide/dist/esm/icons/circle-user-round.js:
lucide/dist/esm/icons/circle-user.js:
lucide/dist/esm/icons/circle.js:
lucide/dist/esm/icons/circuit-board.js:
lucide/dist/esm/icons/circle-x.js:
lucide/dist/esm/icons/citrus.js:
lucide/dist/esm/icons/clapperboard.js:
lucide/dist/esm/icons/clipboard-check.js:
lucide/dist/esm/icons/clipboard-copy.js:
lucide/dist/esm/icons/clipboard-list.js:
lucide/dist/esm/icons/clipboard-minus.js:
lucide/dist/esm/icons/clipboard-paste.js:
lucide/dist/esm/icons/clipboard-pen-line.js:
lucide/dist/esm/icons/clipboard-pen.js:
lucide/dist/esm/icons/clipboard-plus.js:
lucide/dist/esm/icons/clipboard-type.js:
lucide/dist/esm/icons/clipboard-x.js:
lucide/dist/esm/icons/clipboard.js:
lucide/dist/esm/icons/clock-1.js:
lucide/dist/esm/icons/clock-10.js:
lucide/dist/esm/icons/clock-11.js:
lucide/dist/esm/icons/clock-12.js:
lucide/dist/esm/icons/clock-2.js:
lucide/dist/esm/icons/clock-3.js:
lucide/dist/esm/icons/clock-4.js:
lucide/dist/esm/icons/clock-5.js:
lucide/dist/esm/icons/clock-6.js:
lucide/dist/esm/icons/clock-7.js:
lucide/dist/esm/icons/clock-9.js:
lucide/dist/esm/icons/clock-8.js:
lucide/dist/esm/icons/clock-alert.js:
lucide/dist/esm/icons/clock-arrow-down.js:
lucide/dist/esm/icons/clock-arrow-up.js:
lucide/dist/esm/icons/clock-fading.js:
lucide/dist/esm/icons/clock-plus.js:
lucide/dist/esm/icons/clock.js:
lucide/dist/esm/icons/cloud-alert.js:
lucide/dist/esm/icons/cloud-cog.js:
lucide/dist/esm/icons/cloud-download.js:
lucide/dist/esm/icons/cloud-fog.js:
lucide/dist/esm/icons/cloud-drizzle.js:
lucide/dist/esm/icons/cloud-hail.js:
lucide/dist/esm/icons/cloud-lightning.js:
lucide/dist/esm/icons/cloud-moon-rain.js:
lucide/dist/esm/icons/cloud-moon.js:
lucide/dist/esm/icons/cloud-off.js:
lucide/dist/esm/icons/cloud-rain-wind.js:
lucide/dist/esm/icons/cloud-rain.js:
lucide/dist/esm/icons/cloud-snow.js:
lucide/dist/esm/icons/cloud-sun-rain.js:
lucide/dist/esm/icons/cloud-sun.js:
lucide/dist/esm/icons/cloud-upload.js:
lucide/dist/esm/icons/cloud.js:
lucide/dist/esm/icons/clover.js:
lucide/dist/esm/icons/cloudy.js:
lucide/dist/esm/icons/club.js:
lucide/dist/esm/icons/code-xml.js:
lucide/dist/esm/icons/code.js:
lucide/dist/esm/icons/codepen.js:
lucide/dist/esm/icons/codesandbox.js:
lucide/dist/esm/icons/coffee.js:
lucide/dist/esm/icons/cog.js:
lucide/dist/esm/icons/coins.js:
lucide/dist/esm/icons/columns-2.js:
lucide/dist/esm/icons/columns-3-cog.js:
lucide/dist/esm/icons/columns-3.js:
lucide/dist/esm/icons/columns-4.js:
lucide/dist/esm/icons/combine.js:
lucide/dist/esm/icons/command.js:
lucide/dist/esm/icons/component.js:
lucide/dist/esm/icons/compass.js:
lucide/dist/esm/icons/computer.js:
lucide/dist/esm/icons/concierge-bell.js:
lucide/dist/esm/icons/cone.js:
lucide/dist/esm/icons/construction.js:
lucide/dist/esm/icons/contact-round.js:
lucide/dist/esm/icons/contact.js:
lucide/dist/esm/icons/container.js:
lucide/dist/esm/icons/contrast.js:
lucide/dist/esm/icons/cookie.js:
lucide/dist/esm/icons/cooking-pot.js:
lucide/dist/esm/icons/copy-check.js:
lucide/dist/esm/icons/copy-minus.js:
lucide/dist/esm/icons/copy-plus.js:
lucide/dist/esm/icons/copy-slash.js:
lucide/dist/esm/icons/copy-x.js:
lucide/dist/esm/icons/copy.js:
lucide/dist/esm/icons/copyleft.js:
lucide/dist/esm/icons/copyright.js:
lucide/dist/esm/icons/corner-down-left.js:
lucide/dist/esm/icons/corner-down-right.js:
lucide/dist/esm/icons/corner-left-down.js:
lucide/dist/esm/icons/corner-left-up.js:
lucide/dist/esm/icons/corner-right-down.js:
lucide/dist/esm/icons/corner-right-up.js:
lucide/dist/esm/icons/corner-up-left.js:
lucide/dist/esm/icons/corner-up-right.js:
lucide/dist/esm/icons/cpu.js:
lucide/dist/esm/icons/creative-commons.js:
lucide/dist/esm/icons/credit-card.js:
lucide/dist/esm/icons/croissant.js:
lucide/dist/esm/icons/crop.js:
lucide/dist/esm/icons/cross.js:
lucide/dist/esm/icons/crosshair.js:
lucide/dist/esm/icons/crown.js:
lucide/dist/esm/icons/cuboid.js:
lucide/dist/esm/icons/cup-soda.js:
lucide/dist/esm/icons/currency.js:
lucide/dist/esm/icons/cylinder.js:
lucide/dist/esm/icons/database-backup.js:
lucide/dist/esm/icons/database-zap.js:
lucide/dist/esm/icons/database.js:
lucide/dist/esm/icons/dam.js:
lucide/dist/esm/icons/decimals-arrow-left.js:
lucide/dist/esm/icons/decimals-arrow-right.js:
lucide/dist/esm/icons/delete.js:
lucide/dist/esm/icons/dessert.js:
lucide/dist/esm/icons/diameter.js:
lucide/dist/esm/icons/diamond-minus.js:
lucide/dist/esm/icons/diamond-percent.js:
lucide/dist/esm/icons/diamond-plus.js:
lucide/dist/esm/icons/diamond.js:
lucide/dist/esm/icons/dice-1.js:
lucide/dist/esm/icons/dice-2.js:
lucide/dist/esm/icons/dice-3.js:
lucide/dist/esm/icons/dice-4.js:
lucide/dist/esm/icons/dice-5.js:
lucide/dist/esm/icons/dice-6.js:
lucide/dist/esm/icons/dices.js:
lucide/dist/esm/icons/diff.js:
lucide/dist/esm/icons/disc-2.js:
lucide/dist/esm/icons/disc-3.js:
lucide/dist/esm/icons/disc.js:
lucide/dist/esm/icons/disc-album.js:
lucide/dist/esm/icons/divide.js:
lucide/dist/esm/icons/dna-off.js:
lucide/dist/esm/icons/dock.js:
lucide/dist/esm/icons/dna.js:
lucide/dist/esm/icons/dog.js:
lucide/dist/esm/icons/dollar-sign.js:
lucide/dist/esm/icons/donut.js:
lucide/dist/esm/icons/door-closed.js:
lucide/dist/esm/icons/door-closed-locked.js:
lucide/dist/esm/icons/door-open.js:
lucide/dist/esm/icons/dot.js:
lucide/dist/esm/icons/download.js:
lucide/dist/esm/icons/drafting-compass.js:
lucide/dist/esm/icons/dribbble.js:
lucide/dist/esm/icons/drama.js:
lucide/dist/esm/icons/drill.js:
lucide/dist/esm/icons/droplet-off.js:
lucide/dist/esm/icons/droplet.js:
lucide/dist/esm/icons/droplets.js:
lucide/dist/esm/icons/drum.js:
lucide/dist/esm/icons/drumstick.js:
lucide/dist/esm/icons/dumbbell.js:
lucide/dist/esm/icons/ear.js:
lucide/dist/esm/icons/ear-off.js:
lucide/dist/esm/icons/earth-lock.js:
lucide/dist/esm/icons/earth.js:
lucide/dist/esm/icons/eclipse.js:
lucide/dist/esm/icons/egg-fried.js:
lucide/dist/esm/icons/egg-off.js:
lucide/dist/esm/icons/egg.js:
lucide/dist/esm/icons/ellipsis-vertical.js:
lucide/dist/esm/icons/ellipsis.js:
lucide/dist/esm/icons/equal-approximately.js:
lucide/dist/esm/icons/equal-not.js:
lucide/dist/esm/icons/equal.js:
lucide/dist/esm/icons/eraser.js:
lucide/dist/esm/icons/ethernet-port.js:
lucide/dist/esm/icons/euro.js:
lucide/dist/esm/icons/external-link.js:
lucide/dist/esm/icons/eye-closed.js:
lucide/dist/esm/icons/expand.js:
lucide/dist/esm/icons/eye-off.js:
lucide/dist/esm/icons/facebook.js:
lucide/dist/esm/icons/eye.js:
lucide/dist/esm/icons/factory.js:
lucide/dist/esm/icons/fan.js:
lucide/dist/esm/icons/fast-forward.js:
lucide/dist/esm/icons/feather.js:
lucide/dist/esm/icons/fence.js:
lucide/dist/esm/icons/ferris-wheel.js:
lucide/dist/esm/icons/figma.js:
lucide/dist/esm/icons/file-archive.js:
lucide/dist/esm/icons/file-audio-2.js:
lucide/dist/esm/icons/file-audio.js:
lucide/dist/esm/icons/file-axis-3d.js:
lucide/dist/esm/icons/file-badge.js:
lucide/dist/esm/icons/file-badge-2.js:
lucide/dist/esm/icons/file-box.js:
lucide/dist/esm/icons/file-chart-column-increasing.js:
lucide/dist/esm/icons/file-chart-column.js:
lucide/dist/esm/icons/file-chart-pie.js:
lucide/dist/esm/icons/file-chart-line.js:
lucide/dist/esm/icons/file-check-2.js:
lucide/dist/esm/icons/file-check.js:
lucide/dist/esm/icons/file-clock.js:
lucide/dist/esm/icons/file-code-2.js:
lucide/dist/esm/icons/file-code.js:
lucide/dist/esm/icons/file-cog.js:
lucide/dist/esm/icons/file-diff.js:
lucide/dist/esm/icons/file-digit.js:
lucide/dist/esm/icons/file-down.js:
lucide/dist/esm/icons/file-heart.js:
lucide/dist/esm/icons/file-image.js:
lucide/dist/esm/icons/file-input.js:
lucide/dist/esm/icons/file-json-2.js:
lucide/dist/esm/icons/file-json.js:
lucide/dist/esm/icons/file-key-2.js:
lucide/dist/esm/icons/file-key.js:
lucide/dist/esm/icons/file-lock-2.js:
lucide/dist/esm/icons/file-lock.js:
lucide/dist/esm/icons/file-minus-2.js:
lucide/dist/esm/icons/file-minus.js:
lucide/dist/esm/icons/file-music.js:
lucide/dist/esm/icons/file-output.js:
lucide/dist/esm/icons/file-pen-line.js:
lucide/dist/esm/icons/file-pen.js:
lucide/dist/esm/icons/file-plus-2.js:
lucide/dist/esm/icons/file-plus.js:
lucide/dist/esm/icons/file-question.js:
lucide/dist/esm/icons/file-scan.js:
lucide/dist/esm/icons/file-search-2.js:
lucide/dist/esm/icons/file-sliders.js:
lucide/dist/esm/icons/file-search.js:
lucide/dist/esm/icons/file-spreadsheet.js:
lucide/dist/esm/icons/file-stack.js:
lucide/dist/esm/icons/file-symlink.js:
lucide/dist/esm/icons/file-terminal.js:
lucide/dist/esm/icons/file-text.js:
lucide/dist/esm/icons/file-type-2.js:
lucide/dist/esm/icons/file-type.js:
lucide/dist/esm/icons/file-up.js:
lucide/dist/esm/icons/file-user.js:
lucide/dist/esm/icons/file-video-2.js:
lucide/dist/esm/icons/file-volume-2.js:
lucide/dist/esm/icons/file-video.js:
lucide/dist/esm/icons/file-warning.js:
lucide/dist/esm/icons/file-volume.js:
lucide/dist/esm/icons/file-x-2.js:
lucide/dist/esm/icons/file.js:
lucide/dist/esm/icons/file-x.js:
lucide/dist/esm/icons/files.js:
lucide/dist/esm/icons/film.js:
lucide/dist/esm/icons/fire-extinguisher.js:
lucide/dist/esm/icons/fingerprint.js:
lucide/dist/esm/icons/fish-off.js:
lucide/dist/esm/icons/fish-symbol.js:
lucide/dist/esm/icons/fish.js:
lucide/dist/esm/icons/flag-off.js:
lucide/dist/esm/icons/flag-triangle-left.js:
lucide/dist/esm/icons/flag-triangle-right.js:
lucide/dist/esm/icons/flag.js:
lucide/dist/esm/icons/flame-kindling.js:
lucide/dist/esm/icons/flame.js:
lucide/dist/esm/icons/flashlight-off.js:
lucide/dist/esm/icons/flashlight.js:
lucide/dist/esm/icons/flask-conical-off.js:
lucide/dist/esm/icons/flask-conical.js:
lucide/dist/esm/icons/flask-round.js:
lucide/dist/esm/icons/flip-horizontal-2.js:
lucide/dist/esm/icons/flip-horizontal.js:
lucide/dist/esm/icons/flip-vertical-2.js:
lucide/dist/esm/icons/flip-vertical.js:
lucide/dist/esm/icons/flower-2.js:
lucide/dist/esm/icons/focus.js:
lucide/dist/esm/icons/flower.js:
lucide/dist/esm/icons/fold-horizontal.js:
lucide/dist/esm/icons/folder-archive.js:
lucide/dist/esm/icons/fold-vertical.js:
lucide/dist/esm/icons/folder-check.js:
lucide/dist/esm/icons/folder-clock.js:
lucide/dist/esm/icons/folder-closed.js:
lucide/dist/esm/icons/folder-code.js:
lucide/dist/esm/icons/folder-cog.js:
lucide/dist/esm/icons/folder-dot.js:
lucide/dist/esm/icons/folder-down.js:
lucide/dist/esm/icons/folder-git.js:
lucide/dist/esm/icons/folder-heart.js:
lucide/dist/esm/icons/folder-git-2.js:
lucide/dist/esm/icons/folder-input.js:
lucide/dist/esm/icons/folder-kanban.js:
lucide/dist/esm/icons/folder-key.js:
lucide/dist/esm/icons/folder-lock.js:
lucide/dist/esm/icons/folder-minus.js:
lucide/dist/esm/icons/folder-open-dot.js:
lucide/dist/esm/icons/folder-open.js:
lucide/dist/esm/icons/folder-pen.js:
lucide/dist/esm/icons/folder-plus.js:
lucide/dist/esm/icons/folder-output.js:
lucide/dist/esm/icons/folder-root.js:
lucide/dist/esm/icons/folder-search.js:
lucide/dist/esm/icons/folder-search-2.js:
lucide/dist/esm/icons/folder-symlink.js:
lucide/dist/esm/icons/folder-tree.js:
lucide/dist/esm/icons/folder-sync.js:
lucide/dist/esm/icons/folder-up.js:
lucide/dist/esm/icons/folder-x.js:
lucide/dist/esm/icons/folder.js:
lucide/dist/esm/icons/folders.js:
lucide/dist/esm/icons/footprints.js:
lucide/dist/esm/icons/forklift.js:
lucide/dist/esm/icons/forward.js:
lucide/dist/esm/icons/frame.js:
lucide/dist/esm/icons/framer.js:
lucide/dist/esm/icons/frown.js:
lucide/dist/esm/icons/fuel.js:
lucide/dist/esm/icons/fullscreen.js:
lucide/dist/esm/icons/funnel-plus.js:
lucide/dist/esm/icons/funnel-x.js:
lucide/dist/esm/icons/funnel.js:
lucide/dist/esm/icons/gallery-horizontal-end.js:
lucide/dist/esm/icons/gallery-horizontal.js:
lucide/dist/esm/icons/gallery-thumbnails.js:
lucide/dist/esm/icons/gallery-vertical-end.js:
lucide/dist/esm/icons/gallery-vertical.js:
lucide/dist/esm/icons/gamepad-2.js:
lucide/dist/esm/icons/gauge.js:
lucide/dist/esm/icons/gamepad.js:
lucide/dist/esm/icons/gavel.js:
lucide/dist/esm/icons/gem.js:
lucide/dist/esm/icons/ghost.js:
lucide/dist/esm/icons/gift.js:
lucide/dist/esm/icons/git-branch-plus.js:
lucide/dist/esm/icons/git-branch.js:
lucide/dist/esm/icons/git-commit-horizontal.js:
lucide/dist/esm/icons/git-commit-vertical.js:
lucide/dist/esm/icons/git-compare.js:
lucide/dist/esm/icons/git-compare-arrows.js:
lucide/dist/esm/icons/git-fork.js:
lucide/dist/esm/icons/git-graph.js:
lucide/dist/esm/icons/git-merge.js:
lucide/dist/esm/icons/git-pull-request-arrow.js:
lucide/dist/esm/icons/git-pull-request-closed.js:
lucide/dist/esm/icons/git-pull-request-create-arrow.js:
lucide/dist/esm/icons/git-pull-request-create.js:
lucide/dist/esm/icons/git-pull-request-draft.js:
lucide/dist/esm/icons/github.js:
lucide/dist/esm/icons/git-pull-request.js:
lucide/dist/esm/icons/gitlab.js:
lucide/dist/esm/icons/glass-water.js:
lucide/dist/esm/icons/glasses.js:
lucide/dist/esm/icons/globe-lock.js:
lucide/dist/esm/icons/globe.js:
lucide/dist/esm/icons/goal.js:
lucide/dist/esm/icons/grab.js:
lucide/dist/esm/icons/graduation-cap.js:
lucide/dist/esm/icons/grape.js:
lucide/dist/esm/icons/grid-2x2-check.js:
lucide/dist/esm/icons/grid-2x2-plus.js:
lucide/dist/esm/icons/grid-2x2-x.js:
lucide/dist/esm/icons/grid-2x2.js:
lucide/dist/esm/icons/grid-3x3.js:
lucide/dist/esm/icons/grip-horizontal.js:
lucide/dist/esm/icons/grip-vertical.js:
lucide/dist/esm/icons/grip.js:
lucide/dist/esm/icons/group.js:
lucide/dist/esm/icons/guitar.js:
lucide/dist/esm/icons/ham.js:
lucide/dist/esm/icons/hamburger.js:
lucide/dist/esm/icons/hammer.js:
lucide/dist/esm/icons/hand-coins.js:
lucide/dist/esm/icons/hand-heart.js:
lucide/dist/esm/icons/hand-helping.js:
lucide/dist/esm/icons/hand-metal.js:
lucide/dist/esm/icons/hand-platter.js:
lucide/dist/esm/icons/hand.js:
lucide/dist/esm/icons/handshake.js:
lucide/dist/esm/icons/hard-drive-download.js:
lucide/dist/esm/icons/hard-drive-upload.js:
lucide/dist/esm/icons/hard-drive.js:
lucide/dist/esm/icons/hard-hat.js:
lucide/dist/esm/icons/hash.js:
lucide/dist/esm/icons/haze.js:
lucide/dist/esm/icons/hdmi-port.js:
lucide/dist/esm/icons/heading-1.js:
lucide/dist/esm/icons/heading-2.js:
lucide/dist/esm/icons/heading-3.js:
lucide/dist/esm/icons/heading-5.js:
lucide/dist/esm/icons/heading-6.js:
lucide/dist/esm/icons/heading-4.js:
lucide/dist/esm/icons/heading.js:
lucide/dist/esm/icons/headphone-off.js:
lucide/dist/esm/icons/headphones.js:
lucide/dist/esm/icons/headset.js:
lucide/dist/esm/icons/heart-crack.js:
lucide/dist/esm/icons/heart-handshake.js:
lucide/dist/esm/icons/heart-off.js:
lucide/dist/esm/icons/heart-minus.js:
lucide/dist/esm/icons/heart-plus.js:
lucide/dist/esm/icons/heart-pulse.js:
lucide/dist/esm/icons/heart.js:
lucide/dist/esm/icons/heater.js:
lucide/dist/esm/icons/hexagon.js:
lucide/dist/esm/icons/highlighter.js:
lucide/dist/esm/icons/hop-off.js:
lucide/dist/esm/icons/history.js:
lucide/dist/esm/icons/hop.js:
lucide/dist/esm/icons/hospital.js:
lucide/dist/esm/icons/hotel.js:
lucide/dist/esm/icons/hourglass.js:
lucide/dist/esm/icons/house-plug.js:
lucide/dist/esm/icons/house-wifi.js:
lucide/dist/esm/icons/house-plus.js:
lucide/dist/esm/icons/house.js:
lucide/dist/esm/icons/ice-cream-bowl.js:
lucide/dist/esm/icons/ice-cream-cone.js:
lucide/dist/esm/icons/id-card.js:
lucide/dist/esm/icons/image-down.js:
lucide/dist/esm/icons/image-minus.js:
lucide/dist/esm/icons/image-off.js:
lucide/dist/esm/icons/image-play.js:
lucide/dist/esm/icons/image-plus.js:
lucide/dist/esm/icons/image-up.js:
lucide/dist/esm/icons/image-upscale.js:
lucide/dist/esm/icons/image.js:
lucide/dist/esm/icons/images.js:
lucide/dist/esm/icons/import.js:
lucide/dist/esm/icons/inbox.js:
lucide/dist/esm/icons/indent-decrease.js:
lucide/dist/esm/icons/indent-increase.js:
lucide/dist/esm/icons/indian-rupee.js:
lucide/dist/esm/icons/infinity.js:
lucide/dist/esm/icons/info.js:
lucide/dist/esm/icons/inspection-panel.js:
lucide/dist/esm/icons/instagram.js:
lucide/dist/esm/icons/italic.js:
lucide/dist/esm/icons/iteration-ccw.js:
lucide/dist/esm/icons/iteration-cw.js:
lucide/dist/esm/icons/japanese-yen.js:
lucide/dist/esm/icons/joystick.js:
lucide/dist/esm/icons/kanban.js:
lucide/dist/esm/icons/key-round.js:
lucide/dist/esm/icons/key-square.js:
lucide/dist/esm/icons/key.js:
lucide/dist/esm/icons/keyboard-music.js:
lucide/dist/esm/icons/keyboard.js:
lucide/dist/esm/icons/keyboard-off.js:
lucide/dist/esm/icons/lamp-ceiling.js:
lucide/dist/esm/icons/lamp-floor.js:
lucide/dist/esm/icons/lamp-desk.js:
lucide/dist/esm/icons/lamp-wall-down.js:
lucide/dist/esm/icons/lamp-wall-up.js:
lucide/dist/esm/icons/land-plot.js:
lucide/dist/esm/icons/lamp.js:
lucide/dist/esm/icons/landmark.js:
lucide/dist/esm/icons/languages.js:
lucide/dist/esm/icons/laptop-minimal-check.js:
lucide/dist/esm/icons/laptop-minimal.js:
lucide/dist/esm/icons/laptop.js:
lucide/dist/esm/icons/lasso-select.js:
lucide/dist/esm/icons/lasso.js:
lucide/dist/esm/icons/laugh.js:
lucide/dist/esm/icons/layers-2.js:
lucide/dist/esm/icons/layers.js:
lucide/dist/esm/icons/layout-dashboard.js:
lucide/dist/esm/icons/layout-grid.js:
lucide/dist/esm/icons/layout-list.js:
lucide/dist/esm/icons/layout-panel-left.js:
lucide/dist/esm/icons/layout-panel-top.js:
lucide/dist/esm/icons/layout-template.js:
lucide/dist/esm/icons/leaf.js:
lucide/dist/esm/icons/leafy-green.js:
lucide/dist/esm/icons/lectern.js:
lucide/dist/esm/icons/letter-text.js:
lucide/dist/esm/icons/library-big.js:
lucide/dist/esm/icons/library.js:
lucide/dist/esm/icons/life-buoy.js:
lucide/dist/esm/icons/ligature.js:
lucide/dist/esm/icons/lightbulb-off.js:
lucide/dist/esm/icons/lightbulb.js:
lucide/dist/esm/icons/link-2-off.js:
lucide/dist/esm/icons/link-2.js:
lucide/dist/esm/icons/link.js:
lucide/dist/esm/icons/linkedin.js:
lucide/dist/esm/icons/list-check.js:
lucide/dist/esm/icons/list-checks.js:
lucide/dist/esm/icons/list-collapse.js:
lucide/dist/esm/icons/list-end.js:
lucide/dist/esm/icons/list-filter-plus.js:
lucide/dist/esm/icons/list-filter.js:
lucide/dist/esm/icons/list-minus.js:
lucide/dist/esm/icons/list-music.js:
lucide/dist/esm/icons/list-plus.js:
lucide/dist/esm/icons/list-ordered.js:
lucide/dist/esm/icons/list-restart.js:
lucide/dist/esm/icons/list-start.js:
lucide/dist/esm/icons/list-todo.js:
lucide/dist/esm/icons/list-tree.js:
lucide/dist/esm/icons/list-video.js:
lucide/dist/esm/icons/list-x.js:
lucide/dist/esm/icons/list.js:
lucide/dist/esm/icons/loader-circle.js:
lucide/dist/esm/icons/loader.js:
lucide/dist/esm/icons/locate-fixed.js:
lucide/dist/esm/icons/loader-pinwheel.js:
lucide/dist/esm/icons/locate-off.js:
lucide/dist/esm/icons/locate.js:
lucide/dist/esm/icons/location-edit.js:
lucide/dist/esm/icons/lock-keyhole-open.js:
lucide/dist/esm/icons/lock-keyhole.js:
lucide/dist/esm/icons/lock-open.js:
lucide/dist/esm/icons/lock.js:
lucide/dist/esm/icons/log-in.js:
lucide/dist/esm/icons/log-out.js:
lucide/dist/esm/icons/logs.js:
lucide/dist/esm/icons/lollipop.js:
lucide/dist/esm/icons/luggage.js:
lucide/dist/esm/icons/magnet.js:
lucide/dist/esm/icons/mail-check.js:
lucide/dist/esm/icons/mail-minus.js:
lucide/dist/esm/icons/mail-open.js:
lucide/dist/esm/icons/mail-plus.js:
lucide/dist/esm/icons/mail-question.js:
lucide/dist/esm/icons/mail-warning.js:
lucide/dist/esm/icons/mail-search.js:
lucide/dist/esm/icons/mail-x.js:
lucide/dist/esm/icons/mail.js:
lucide/dist/esm/icons/mailbox.js:
lucide/dist/esm/icons/mails.js:
lucide/dist/esm/icons/map-pin-check-inside.js:
lucide/dist/esm/icons/map-pin-check.js:
lucide/dist/esm/icons/map-pin-house.js:
lucide/dist/esm/icons/map-pin-minus-inside.js:
lucide/dist/esm/icons/map-pin-minus.js:
lucide/dist/esm/icons/map-pin-off.js:
lucide/dist/esm/icons/map-pin-plus-inside.js:
lucide/dist/esm/icons/map-pin-x-inside.js:
lucide/dist/esm/icons/map-pin-plus.js:
lucide/dist/esm/icons/map-pin.js:
lucide/dist/esm/icons/map-pin-x.js:
lucide/dist/esm/icons/map-pinned.js:
lucide/dist/esm/icons/map-plus.js:
lucide/dist/esm/icons/map.js:
lucide/dist/esm/icons/mars-stroke.js:
lucide/dist/esm/icons/mars.js:
lucide/dist/esm/icons/martini.js:
lucide/dist/esm/icons/maximize-2.js:
lucide/dist/esm/icons/maximize.js:
lucide/dist/esm/icons/medal.js:
lucide/dist/esm/icons/megaphone-off.js:
lucide/dist/esm/icons/meh.js:
lucide/dist/esm/icons/megaphone.js:
lucide/dist/esm/icons/memory-stick.js:
lucide/dist/esm/icons/menu.js:
lucide/dist/esm/icons/merge.js:
lucide/dist/esm/icons/message-circle-code.js:
lucide/dist/esm/icons/message-circle-dashed.js:
lucide/dist/esm/icons/message-circle-heart.js:
lucide/dist/esm/icons/message-circle-more.js:
lucide/dist/esm/icons/message-circle-plus.js:
lucide/dist/esm/icons/message-circle-question.js:
lucide/dist/esm/icons/message-circle-off.js:
lucide/dist/esm/icons/message-circle-reply.js:
lucide/dist/esm/icons/message-circle-warning.js:
lucide/dist/esm/icons/message-circle-x.js:
lucide/dist/esm/icons/message-circle.js:
lucide/dist/esm/icons/message-square-code.js:
lucide/dist/esm/icons/message-square-dashed.js:
lucide/dist/esm/icons/message-square-dot.js:
lucide/dist/esm/icons/message-square-heart.js:
lucide/dist/esm/icons/message-square-diff.js:
lucide/dist/esm/icons/message-square-lock.js:
lucide/dist/esm/icons/message-square-more.js:
lucide/dist/esm/icons/message-square-off.js:
lucide/dist/esm/icons/message-square-plus.js:
lucide/dist/esm/icons/message-square-quote.js:
lucide/dist/esm/icons/message-square-share.js:
lucide/dist/esm/icons/message-square-reply.js:
lucide/dist/esm/icons/message-square-text.js:
lucide/dist/esm/icons/message-square-warning.js:
lucide/dist/esm/icons/message-square-x.js:
lucide/dist/esm/icons/message-square.js:
lucide/dist/esm/icons/messages-square.js:
lucide/dist/esm/icons/mic-off.js:
lucide/dist/esm/icons/mic-vocal.js:
lucide/dist/esm/icons/mic.js:
lucide/dist/esm/icons/microchip.js:
lucide/dist/esm/icons/microwave.js:
lucide/dist/esm/icons/milestone.js:
lucide/dist/esm/icons/microscope.js:
lucide/dist/esm/icons/milk-off.js:
lucide/dist/esm/icons/milk.js:
lucide/dist/esm/icons/minimize-2.js:
lucide/dist/esm/icons/minimize.js:
lucide/dist/esm/icons/monitor-check.js:
lucide/dist/esm/icons/minus.js:
lucide/dist/esm/icons/monitor-cog.js:
lucide/dist/esm/icons/monitor-dot.js:
lucide/dist/esm/icons/monitor-down.js:
lucide/dist/esm/icons/monitor-off.js:
lucide/dist/esm/icons/monitor-pause.js:
lucide/dist/esm/icons/monitor-play.js:
lucide/dist/esm/icons/monitor-speaker.js:
lucide/dist/esm/icons/monitor-smartphone.js:
lucide/dist/esm/icons/monitor-stop.js:
lucide/dist/esm/icons/monitor-up.js:
lucide/dist/esm/icons/monitor-x.js:
lucide/dist/esm/icons/monitor.js:
lucide/dist/esm/icons/moon-star.js:
lucide/dist/esm/icons/mountain-snow.js:
lucide/dist/esm/icons/moon.js:
lucide/dist/esm/icons/mountain.js:
lucide/dist/esm/icons/mouse-off.js:
lucide/dist/esm/icons/mouse-pointer-2.js:
lucide/dist/esm/icons/mouse-pointer-ban.js:
lucide/dist/esm/icons/mouse-pointer-click.js:
lucide/dist/esm/icons/mouse-pointer.js:
lucide/dist/esm/icons/mouse.js:
lucide/dist/esm/icons/move-3d.js:
lucide/dist/esm/icons/move-diagonal-2.js:
lucide/dist/esm/icons/move-diagonal.js:
lucide/dist/esm/icons/move-down-left.js:
lucide/dist/esm/icons/move-down-right.js:
lucide/dist/esm/icons/move-down.js:
lucide/dist/esm/icons/move-horizontal.js:
lucide/dist/esm/icons/move-left.js:
lucide/dist/esm/icons/move-right.js:
lucide/dist/esm/icons/move-up-left.js:
lucide/dist/esm/icons/move-up.js:
lucide/dist/esm/icons/move-up-right.js:
lucide/dist/esm/icons/move-vertical.js:
lucide/dist/esm/icons/move.js:
lucide/dist/esm/icons/music-2.js:
lucide/dist/esm/icons/music-3.js:
lucide/dist/esm/icons/music-4.js:
lucide/dist/esm/icons/music.js:
lucide/dist/esm/icons/navigation-2-off.js:
lucide/dist/esm/icons/navigation-2.js:
lucide/dist/esm/icons/navigation-off.js:
lucide/dist/esm/icons/navigation.js:
lucide/dist/esm/icons/network.js:
lucide/dist/esm/icons/newspaper.js:
lucide/dist/esm/icons/nfc.js:
lucide/dist/esm/icons/non-binary.js:
lucide/dist/esm/icons/notebook-pen.js:
lucide/dist/esm/icons/notebook-tabs.js:
lucide/dist/esm/icons/notebook.js:
lucide/dist/esm/icons/notebook-text.js:
lucide/dist/esm/icons/notepad-text-dashed.js:
lucide/dist/esm/icons/notepad-text.js:
lucide/dist/esm/icons/nut-off.js:
lucide/dist/esm/icons/nut.js:
lucide/dist/esm/icons/octagon-alert.js:
lucide/dist/esm/icons/octagon-minus.js:
lucide/dist/esm/icons/octagon-pause.js:
lucide/dist/esm/icons/octagon.js:
lucide/dist/esm/icons/octagon-x.js:
lucide/dist/esm/icons/omega.js:
lucide/dist/esm/icons/option.js:
lucide/dist/esm/icons/orbit.js:
lucide/dist/esm/icons/origami.js:
lucide/dist/esm/icons/package-2.js:
lucide/dist/esm/icons/package-check.js:
lucide/dist/esm/icons/package-minus.js:
lucide/dist/esm/icons/package-open.js:
lucide/dist/esm/icons/package-plus.js:
lucide/dist/esm/icons/package-search.js:
lucide/dist/esm/icons/package-x.js:
lucide/dist/esm/icons/package.js:
lucide/dist/esm/icons/paint-bucket.js:
lucide/dist/esm/icons/paint-roller.js:
lucide/dist/esm/icons/paintbrush-vertical.js:
lucide/dist/esm/icons/paintbrush.js:
lucide/dist/esm/icons/palette.js:
lucide/dist/esm/icons/panel-bottom-close.js:
lucide/dist/esm/icons/panel-bottom-dashed.js:
lucide/dist/esm/icons/panel-bottom-open.js:
lucide/dist/esm/icons/panel-bottom.js:
lucide/dist/esm/icons/panel-left-close.js:
lucide/dist/esm/icons/panel-left-dashed.js:
lucide/dist/esm/icons/panel-left-open.js:
lucide/dist/esm/icons/panel-left.js:
lucide/dist/esm/icons/panel-right-close.js:
lucide/dist/esm/icons/panel-right-dashed.js:
lucide/dist/esm/icons/panel-right.js:
lucide/dist/esm/icons/panel-right-open.js:
lucide/dist/esm/icons/panel-top-close.js:
lucide/dist/esm/icons/panel-top-dashed.js:
lucide/dist/esm/icons/panel-top-open.js:
lucide/dist/esm/icons/panel-top.js:
lucide/dist/esm/icons/panels-left-bottom.js:
lucide/dist/esm/icons/panels-right-bottom.js:
lucide/dist/esm/icons/panels-top-left.js:
lucide/dist/esm/icons/paperclip.js:
lucide/dist/esm/icons/parentheses.js:
lucide/dist/esm/icons/parking-meter.js:
lucide/dist/esm/icons/party-popper.js:
lucide/dist/esm/icons/pause.js:
lucide/dist/esm/icons/paw-print.js:
lucide/dist/esm/icons/pc-case.js:
lucide/dist/esm/icons/pen-line.js:
lucide/dist/esm/icons/pen-off.js:
lucide/dist/esm/icons/pen-tool.js:
lucide/dist/esm/icons/pen.js:
lucide/dist/esm/icons/pencil-line.js:
lucide/dist/esm/icons/pencil-off.js:
lucide/dist/esm/icons/pencil-ruler.js:
lucide/dist/esm/icons/pencil.js:
lucide/dist/esm/icons/pentagon.js:
lucide/dist/esm/icons/percent.js:
lucide/dist/esm/icons/person-standing.js:
lucide/dist/esm/icons/philippine-peso.js:
lucide/dist/esm/icons/phone-call.js:
lucide/dist/esm/icons/phone-forwarded.js:
lucide/dist/esm/icons/phone-incoming.js:
lucide/dist/esm/icons/phone-missed.js:
lucide/dist/esm/icons/phone-off.js:
lucide/dist/esm/icons/phone-outgoing.js:
lucide/dist/esm/icons/phone.js:
lucide/dist/esm/icons/piano.js:
lucide/dist/esm/icons/pi.js:
lucide/dist/esm/icons/pickaxe.js:
lucide/dist/esm/icons/picture-in-picture-2.js:
lucide/dist/esm/icons/piggy-bank.js:
lucide/dist/esm/icons/picture-in-picture.js:
lucide/dist/esm/icons/pilcrow-left.js:
lucide/dist/esm/icons/pilcrow-right.js:
lucide/dist/esm/icons/pilcrow.js:
lucide/dist/esm/icons/pill-bottle.js:
lucide/dist/esm/icons/pill.js:
lucide/dist/esm/icons/pin-off.js:
lucide/dist/esm/icons/pin.js:
lucide/dist/esm/icons/plane-landing.js:
lucide/dist/esm/icons/pipette.js:
lucide/dist/esm/icons/pizza.js:
lucide/dist/esm/icons/plane-takeoff.js:
lucide/dist/esm/icons/plane.js:
lucide/dist/esm/icons/play.js:
lucide/dist/esm/icons/plug-2.js:
lucide/dist/esm/icons/plug-zap.js:
lucide/dist/esm/icons/plug.js:
lucide/dist/esm/icons/plus.js:
lucide/dist/esm/icons/pocket-knife.js:
lucide/dist/esm/icons/pocket.js:
lucide/dist/esm/icons/podcast.js:
lucide/dist/esm/icons/pointer-off.js:
lucide/dist/esm/icons/pointer.js:
lucide/dist/esm/icons/popcorn.js:
lucide/dist/esm/icons/popsicle.js:
lucide/dist/esm/icons/power-off.js:
lucide/dist/esm/icons/pound-sterling.js:
lucide/dist/esm/icons/power.js:
lucide/dist/esm/icons/presentation.js:
lucide/dist/esm/icons/printer-check.js:
lucide/dist/esm/icons/printer.js:
lucide/dist/esm/icons/projector.js:
lucide/dist/esm/icons/proportions.js:
lucide/dist/esm/icons/puzzle.js:
lucide/dist/esm/icons/pyramid.js:
lucide/dist/esm/icons/qr-code.js:
lucide/dist/esm/icons/quote.js:
lucide/dist/esm/icons/rabbit.js:
lucide/dist/esm/icons/radar.js:
lucide/dist/esm/icons/radiation.js:
lucide/dist/esm/icons/radical.js:
lucide/dist/esm/icons/radio-receiver.js:
lucide/dist/esm/icons/radio-tower.js:
lucide/dist/esm/icons/radio.js:
lucide/dist/esm/icons/radius.js:
lucide/dist/esm/icons/rail-symbol.js:
lucide/dist/esm/icons/rainbow.js:
lucide/dist/esm/icons/rat.js:
lucide/dist/esm/icons/ratio.js:
lucide/dist/esm/icons/receipt-cent.js:
lucide/dist/esm/icons/receipt-indian-rupee.js:
lucide/dist/esm/icons/receipt-euro.js:
lucide/dist/esm/icons/receipt-japanese-yen.js:
lucide/dist/esm/icons/receipt-pound-sterling.js:
lucide/dist/esm/icons/receipt-russian-ruble.js:
lucide/dist/esm/icons/receipt-swiss-franc.js:
lucide/dist/esm/icons/receipt.js:
lucide/dist/esm/icons/receipt-text.js:
lucide/dist/esm/icons/rectangle-ellipsis.js:
lucide/dist/esm/icons/rectangle-horizontal.js:
lucide/dist/esm/icons/rectangle-goggles.js:
lucide/dist/esm/icons/rectangle-vertical.js:
lucide/dist/esm/icons/recycle.js:
lucide/dist/esm/icons/redo-2.js:
lucide/dist/esm/icons/redo-dot.js:
lucide/dist/esm/icons/redo.js:
lucide/dist/esm/icons/refresh-ccw-dot.js:
lucide/dist/esm/icons/refresh-cw-off.js:
lucide/dist/esm/icons/refresh-ccw.js:
lucide/dist/esm/icons/refresh-cw.js:
lucide/dist/esm/icons/refrigerator.js:
lucide/dist/esm/icons/regex.js:
lucide/dist/esm/icons/remove-formatting.js:
lucide/dist/esm/icons/repeat-1.js:
lucide/dist/esm/icons/repeat-2.js:
lucide/dist/esm/icons/repeat.js:
lucide/dist/esm/icons/replace-all.js:
lucide/dist/esm/icons/replace.js:
lucide/dist/esm/icons/reply-all.js:
lucide/dist/esm/icons/reply.js:
lucide/dist/esm/icons/rewind.js:
lucide/dist/esm/icons/ribbon.js:
lucide/dist/esm/icons/rocket.js:
lucide/dist/esm/icons/rocking-chair.js:
lucide/dist/esm/icons/roller-coaster.js:
lucide/dist/esm/icons/rotate-3d.js:
lucide/dist/esm/icons/rotate-ccw-key.js:
lucide/dist/esm/icons/rotate-ccw-square.js:
lucide/dist/esm/icons/rotate-ccw.js:
lucide/dist/esm/icons/rotate-cw-square.js:
lucide/dist/esm/icons/rotate-cw.js:
lucide/dist/esm/icons/route-off.js:
lucide/dist/esm/icons/route.js:
lucide/dist/esm/icons/router.js:
lucide/dist/esm/icons/rows-2.js:
lucide/dist/esm/icons/rows-4.js:
lucide/dist/esm/icons/rows-3.js:
lucide/dist/esm/icons/rss.js:
lucide/dist/esm/icons/ruler-dimension-line.js:
lucide/dist/esm/icons/ruler.js:
lucide/dist/esm/icons/russian-ruble.js:
lucide/dist/esm/icons/sailboat.js:
lucide/dist/esm/icons/salad.js:
lucide/dist/esm/icons/sandwich.js:
lucide/dist/esm/icons/satellite-dish.js:
lucide/dist/esm/icons/satellite.js:
lucide/dist/esm/icons/saudi-riyal.js:
lucide/dist/esm/icons/save-all.js:
lucide/dist/esm/icons/save-off.js:
lucide/dist/esm/icons/save.js:
lucide/dist/esm/icons/scale-3d.js:
lucide/dist/esm/icons/scale.js:
lucide/dist/esm/icons/scaling.js:
lucide/dist/esm/icons/scan-barcode.js:
lucide/dist/esm/icons/scan-eye.js:
lucide/dist/esm/icons/scan-face.js:
lucide/dist/esm/icons/scan-heart.js:
lucide/dist/esm/icons/scan-qr-code.js:
lucide/dist/esm/icons/scan-line.js:
lucide/dist/esm/icons/scan-search.js:
lucide/dist/esm/icons/scan-text.js:
lucide/dist/esm/icons/scan.js:
lucide/dist/esm/icons/school.js:
lucide/dist/esm/icons/scissors-line-dashed.js:
lucide/dist/esm/icons/scissors.js:
lucide/dist/esm/icons/screen-share-off.js:
lucide/dist/esm/icons/screen-share.js:
lucide/dist/esm/icons/scroll-text.js:
lucide/dist/esm/icons/scroll.js:
lucide/dist/esm/icons/search-check.js:
lucide/dist/esm/icons/search-code.js:
lucide/dist/esm/icons/search-slash.js:
lucide/dist/esm/icons/search-x.js:
lucide/dist/esm/icons/section.js:
lucide/dist/esm/icons/search.js:
lucide/dist/esm/icons/send-horizontal.js:
lucide/dist/esm/icons/send-to-back.js:
lucide/dist/esm/icons/send.js:
lucide/dist/esm/icons/separator-horizontal.js:
lucide/dist/esm/icons/separator-vertical.js:
lucide/dist/esm/icons/server-cog.js:
lucide/dist/esm/icons/server-crash.js:
lucide/dist/esm/icons/server-off.js:
lucide/dist/esm/icons/server.js:
lucide/dist/esm/icons/settings-2.js:
lucide/dist/esm/icons/settings.js:
lucide/dist/esm/icons/shapes.js:
lucide/dist/esm/icons/share-2.js:
lucide/dist/esm/icons/sheet.js:
lucide/dist/esm/icons/share.js:
lucide/dist/esm/icons/shell.js:
lucide/dist/esm/icons/shield-alert.js:
lucide/dist/esm/icons/shield-ban.js:
lucide/dist/esm/icons/shield-check.js:
lucide/dist/esm/icons/shield-ellipsis.js:
lucide/dist/esm/icons/shield-half.js:
lucide/dist/esm/icons/shield-minus.js:
lucide/dist/esm/icons/shield-off.js:
lucide/dist/esm/icons/shield-plus.js:
lucide/dist/esm/icons/shield-question.js:
lucide/dist/esm/icons/shield-user.js:
lucide/dist/esm/icons/shield-x.js:
lucide/dist/esm/icons/ship-wheel.js:
lucide/dist/esm/icons/ship.js:
lucide/dist/esm/icons/shield.js:
lucide/dist/esm/icons/shirt.js:
lucide/dist/esm/icons/shopping-basket.js:
lucide/dist/esm/icons/shopping-bag.js:
lucide/dist/esm/icons/shopping-cart.js:
lucide/dist/esm/icons/shovel.js:
lucide/dist/esm/icons/shredder.js:
lucide/dist/esm/icons/shower-head.js:
lucide/dist/esm/icons/shrimp.js:
lucide/dist/esm/icons/shrink.js:
lucide/dist/esm/icons/shrub.js:
lucide/dist/esm/icons/shuffle.js:
lucide/dist/esm/icons/sigma.js:
lucide/dist/esm/icons/signal-high.js:
lucide/dist/esm/icons/signal-medium.js:
lucide/dist/esm/icons/signal-low.js:
lucide/dist/esm/icons/signal-zero.js:
lucide/dist/esm/icons/signature.js:
lucide/dist/esm/icons/signal.js:
lucide/dist/esm/icons/signpost-big.js:
lucide/dist/esm/icons/signpost.js:
lucide/dist/esm/icons/siren.js:
lucide/dist/esm/icons/skip-forward.js:
lucide/dist/esm/icons/skip-back.js:
lucide/dist/esm/icons/skull.js:
lucide/dist/esm/icons/slack.js:
lucide/dist/esm/icons/slash.js:
lucide/dist/esm/icons/slice.js:
lucide/dist/esm/icons/sliders-horizontal.js:
lucide/dist/esm/icons/sliders-vertical.js:
lucide/dist/esm/icons/smartphone-charging.js:
lucide/dist/esm/icons/smartphone-nfc.js:
lucide/dist/esm/icons/smartphone.js:
lucide/dist/esm/icons/smile-plus.js:
lucide/dist/esm/icons/smile.js:
lucide/dist/esm/icons/snail.js:
lucide/dist/esm/icons/snowflake.js:
lucide/dist/esm/icons/soap-dispenser-droplet.js:
lucide/dist/esm/icons/sofa.js:
lucide/dist/esm/icons/soup.js:
lucide/dist/esm/icons/space.js:
lucide/dist/esm/icons/spade.js:
lucide/dist/esm/icons/sparkles.js:
lucide/dist/esm/icons/sparkle.js:
lucide/dist/esm/icons/speaker.js:
lucide/dist/esm/icons/speech.js:
lucide/dist/esm/icons/spell-check-2.js:
lucide/dist/esm/icons/spline-pointer.js:
lucide/dist/esm/icons/split.js:
lucide/dist/esm/icons/spell-check.js:
lucide/dist/esm/icons/spline.js:
lucide/dist/esm/icons/sprout.js:
lucide/dist/esm/icons/spray-can.js:
lucide/dist/esm/icons/square-activity.js:
lucide/dist/esm/icons/square-arrow-down-left.js:
lucide/dist/esm/icons/square-arrow-down-right.js:
lucide/dist/esm/icons/square-arrow-down.js:
lucide/dist/esm/icons/square-arrow-left.js:
lucide/dist/esm/icons/square-arrow-out-down-left.js:
lucide/dist/esm/icons/square-arrow-out-up-left.js:
lucide/dist/esm/icons/square-arrow-out-up-right.js:
lucide/dist/esm/icons/square-arrow-out-down-right.js:
lucide/dist/esm/icons/square-arrow-right.js:
lucide/dist/esm/icons/square-arrow-up-left.js:
lucide/dist/esm/icons/square-arrow-up-right.js:
lucide/dist/esm/icons/square-arrow-up.js:
lucide/dist/esm/icons/square-asterisk.js:
lucide/dist/esm/icons/square-bottom-dashed-scissors.js:
lucide/dist/esm/icons/square-chart-gantt.js:
lucide/dist/esm/icons/square-check-big.js:
lucide/dist/esm/icons/square-check.js:
lucide/dist/esm/icons/square-chevron-down.js:
lucide/dist/esm/icons/square-chevron-left.js:
lucide/dist/esm/icons/square-chevron-right.js:
lucide/dist/esm/icons/square-chevron-up.js:
lucide/dist/esm/icons/square-code.js:
lucide/dist/esm/icons/square-dashed-bottom.js:
lucide/dist/esm/icons/square-dashed-bottom-code.js:
lucide/dist/esm/icons/square-dashed-kanban.js:
lucide/dist/esm/icons/square-dashed-mouse-pointer.js:
lucide/dist/esm/icons/square-dashed.js:
lucide/dist/esm/icons/square-divide.js:
lucide/dist/esm/icons/square-dot.js:
lucide/dist/esm/icons/square-equal.js:
lucide/dist/esm/icons/square-function.js:
lucide/dist/esm/icons/square-kanban.js:
lucide/dist/esm/icons/square-library.js:
lucide/dist/esm/icons/square-m.js:
lucide/dist/esm/icons/square-menu.js:
lucide/dist/esm/icons/square-minus.js:
lucide/dist/esm/icons/square-mouse-pointer.js:
lucide/dist/esm/icons/square-parking-off.js:
lucide/dist/esm/icons/square-parking.js:
lucide/dist/esm/icons/square-pen.js:
lucide/dist/esm/icons/square-percent.js:
lucide/dist/esm/icons/square-pi.js:
lucide/dist/esm/icons/square-pilcrow.js:
lucide/dist/esm/icons/square-play.js:
lucide/dist/esm/icons/square-plus.js:
lucide/dist/esm/icons/square-power.js:
lucide/dist/esm/icons/square-radical.js:
lucide/dist/esm/icons/square-round-corner.js:
lucide/dist/esm/icons/square-scissors.js:
lucide/dist/esm/icons/square-sigma.js:
lucide/dist/esm/icons/square-slash.js:
lucide/dist/esm/icons/square-split-horizontal.js:
lucide/dist/esm/icons/square-split-vertical.js:
lucide/dist/esm/icons/square-square.js:
lucide/dist/esm/icons/square-stack.js:
lucide/dist/esm/icons/square-user-round.js:
lucide/dist/esm/icons/square-terminal.js:
lucide/dist/esm/icons/square-x.js:
lucide/dist/esm/icons/square-user.js:
lucide/dist/esm/icons/square.js:
lucide/dist/esm/icons/squares-exclude.js:
lucide/dist/esm/icons/squares-intersect.js:
lucide/dist/esm/icons/squares-subtract.js:
lucide/dist/esm/icons/squares-unite.js:
lucide/dist/esm/icons/squircle.js:
lucide/dist/esm/icons/squirrel.js:
lucide/dist/esm/icons/stamp.js:
lucide/dist/esm/icons/star-half.js:
lucide/dist/esm/icons/star-off.js:
lucide/dist/esm/icons/star.js:
lucide/dist/esm/icons/step-back.js:
lucide/dist/esm/icons/step-forward.js:
lucide/dist/esm/icons/sticker.js:
lucide/dist/esm/icons/stethoscope.js:
lucide/dist/esm/icons/sticky-note.js:
lucide/dist/esm/icons/store.js:
lucide/dist/esm/icons/stretch-horizontal.js:
lucide/dist/esm/icons/stretch-vertical.js:
lucide/dist/esm/icons/strikethrough.js:
lucide/dist/esm/icons/subscript.js:
lucide/dist/esm/icons/sun-dim.js:
lucide/dist/esm/icons/sun-medium.js:
lucide/dist/esm/icons/sun-moon.js:
lucide/dist/esm/icons/sun.js:
lucide/dist/esm/icons/sunrise.js:
lucide/dist/esm/icons/sun-snow.js:
lucide/dist/esm/icons/sunset.js:
lucide/dist/esm/icons/superscript.js:
lucide/dist/esm/icons/swatch-book.js:
lucide/dist/esm/icons/switch-camera.js:
lucide/dist/esm/icons/sword.js:
lucide/dist/esm/icons/swiss-franc.js:
lucide/dist/esm/icons/swords.js:
lucide/dist/esm/icons/syringe.js:
lucide/dist/esm/icons/table-2.js:
lucide/dist/esm/icons/table-cells-merge.js:
lucide/dist/esm/icons/table-cells-split.js:
lucide/dist/esm/icons/table-columns-split.js:
lucide/dist/esm/icons/table-of-contents.js:
lucide/dist/esm/icons/table-properties.js:
lucide/dist/esm/icons/table-rows-split.js:
lucide/dist/esm/icons/table.js:
lucide/dist/esm/icons/tablet-smartphone.js:
lucide/dist/esm/icons/tablet.js:
lucide/dist/esm/icons/tag.js:
lucide/dist/esm/icons/tags.js:
lucide/dist/esm/icons/tablets.js:
lucide/dist/esm/icons/tally-1.js:
lucide/dist/esm/icons/tally-2.js:
lucide/dist/esm/icons/tally-3.js:
lucide/dist/esm/icons/tally-4.js:
lucide/dist/esm/icons/tally-5.js:
lucide/dist/esm/icons/target.js:
lucide/dist/esm/icons/tangent.js:
lucide/dist/esm/icons/tent-tree.js:
lucide/dist/esm/icons/telescope.js:
lucide/dist/esm/icons/tent.js:
lucide/dist/esm/icons/terminal.js:
lucide/dist/esm/icons/test-tube-diagonal.js:
lucide/dist/esm/icons/test-tube.js:
lucide/dist/esm/icons/test-tubes.js:
lucide/dist/esm/icons/text-cursor-input.js:
lucide/dist/esm/icons/text-cursor.js:
lucide/dist/esm/icons/text-quote.js:
lucide/dist/esm/icons/text-search.js:
lucide/dist/esm/icons/text-select.js:
lucide/dist/esm/icons/theater.js:
lucide/dist/esm/icons/text.js:
lucide/dist/esm/icons/thermometer-sun.js:
lucide/dist/esm/icons/thermometer-snowflake.js:
lucide/dist/esm/icons/thermometer.js:
lucide/dist/esm/icons/thumbs-down.js:
lucide/dist/esm/icons/thumbs-up.js:
lucide/dist/esm/icons/ticket-check.js:
lucide/dist/esm/icons/ticket-minus.js:
lucide/dist/esm/icons/ticket-percent.js:
lucide/dist/esm/icons/ticket-slash.js:
lucide/dist/esm/icons/ticket-plus.js:
lucide/dist/esm/icons/ticket-x.js:
lucide/dist/esm/icons/ticket.js:
lucide/dist/esm/icons/tickets-plane.js:
lucide/dist/esm/icons/tickets.js:
lucide/dist/esm/icons/timer-reset.js:
lucide/dist/esm/icons/timer.js:
lucide/dist/esm/icons/timer-off.js:
lucide/dist/esm/icons/toggle-left.js:
lucide/dist/esm/icons/toggle-right.js:
lucide/dist/esm/icons/toilet.js:
lucide/dist/esm/icons/tornado.js:
lucide/dist/esm/icons/torus.js:
lucide/dist/esm/icons/touchpad-off.js:
lucide/dist/esm/icons/touchpad.js:
lucide/dist/esm/icons/tower-control.js:
lucide/dist/esm/icons/toy-brick.js:
lucide/dist/esm/icons/tractor.js:
lucide/dist/esm/icons/traffic-cone.js:
lucide/dist/esm/icons/train-front-tunnel.js:
lucide/dist/esm/icons/train-front.js:
lucide/dist/esm/icons/train-track.js:
lucide/dist/esm/icons/tram-front.js:
lucide/dist/esm/icons/transgender.js:
lucide/dist/esm/icons/trash-2.js:
lucide/dist/esm/icons/trash.js:
lucide/dist/esm/icons/tree-deciduous.js:
lucide/dist/esm/icons/tree-palm.js:
lucide/dist/esm/icons/trees.js:
lucide/dist/esm/icons/tree-pine.js:
lucide/dist/esm/icons/trello.js:
lucide/dist/esm/icons/trending-down.js:
lucide/dist/esm/icons/trending-up-down.js:
lucide/dist/esm/icons/trending-up.js:
lucide/dist/esm/icons/triangle-alert.js:
lucide/dist/esm/icons/triangle-dashed.js:
lucide/dist/esm/icons/triangle-right.js:
lucide/dist/esm/icons/triangle.js:
lucide/dist/esm/icons/trophy.js:
lucide/dist/esm/icons/truck.js:
lucide/dist/esm/icons/truck-electric.js:
lucide/dist/esm/icons/turtle.js:
lucide/dist/esm/icons/tv-minimal-play.js:
lucide/dist/esm/icons/tv.js:
lucide/dist/esm/icons/twitch.js:
lucide/dist/esm/icons/tv-minimal.js:
lucide/dist/esm/icons/twitter.js:
lucide/dist/esm/icons/type-outline.js:
lucide/dist/esm/icons/type.js:
lucide/dist/esm/icons/umbrella-off.js:
lucide/dist/esm/icons/underline.js:
lucide/dist/esm/icons/umbrella.js:
lucide/dist/esm/icons/undo-2.js:
lucide/dist/esm/icons/undo-dot.js:
lucide/dist/esm/icons/undo.js:
lucide/dist/esm/icons/unfold-horizontal.js:
lucide/dist/esm/icons/ungroup.js:
lucide/dist/esm/icons/unfold-vertical.js:
lucide/dist/esm/icons/university.js:
lucide/dist/esm/icons/unlink.js:
lucide/dist/esm/icons/unlink-2.js:
lucide/dist/esm/icons/unplug.js:
lucide/dist/esm/icons/upload.js:
lucide/dist/esm/icons/usb.js:
lucide/dist/esm/icons/user-check.js:
lucide/dist/esm/icons/user-cog.js:
lucide/dist/esm/icons/user-lock.js:
lucide/dist/esm/icons/user-minus.js:
lucide/dist/esm/icons/user-pen.js:
lucide/dist/esm/icons/user-plus.js:
lucide/dist/esm/icons/user-round-check.js:
lucide/dist/esm/icons/user-round-cog.js:
lucide/dist/esm/icons/user-round-minus.js:
lucide/dist/esm/icons/user-round-pen.js:
lucide/dist/esm/icons/user-round-plus.js:
lucide/dist/esm/icons/user-round-search.js:
lucide/dist/esm/icons/user-round-x.js:
lucide/dist/esm/icons/user-round.js:
lucide/dist/esm/icons/user-search.js:
lucide/dist/esm/icons/user-x.js:
lucide/dist/esm/icons/user.js:
lucide/dist/esm/icons/users-round.js:
lucide/dist/esm/icons/users.js:
lucide/dist/esm/icons/utensils.js:
lucide/dist/esm/icons/utility-pole.js:
lucide/dist/esm/icons/utensils-crossed.js:
lucide/dist/esm/icons/variable.js:
lucide/dist/esm/icons/vault.js:
lucide/dist/esm/icons/venetian-mask.js:
lucide/dist/esm/icons/vegan.js:
lucide/dist/esm/icons/venus-and-mars.js:
lucide/dist/esm/icons/venus.js:
lucide/dist/esm/icons/vibrate-off.js:
lucide/dist/esm/icons/vibrate.js:
lucide/dist/esm/icons/video.js:
lucide/dist/esm/icons/video-off.js:
lucide/dist/esm/icons/videotape.js:
lucide/dist/esm/icons/view.js:
lucide/dist/esm/icons/voicemail.js:
lucide/dist/esm/icons/volleyball.js:
lucide/dist/esm/icons/volume-1.js:
lucide/dist/esm/icons/volume-2.js:
lucide/dist/esm/icons/volume-off.js:
lucide/dist/esm/icons/volume-x.js:
lucide/dist/esm/icons/volume.js:
lucide/dist/esm/icons/vote.js:
lucide/dist/esm/icons/wallet-cards.js:
lucide/dist/esm/icons/wallet-minimal.js:
lucide/dist/esm/icons/wallet.js:
lucide/dist/esm/icons/wallpaper.js:
lucide/dist/esm/icons/wand-sparkles.js:
lucide/dist/esm/icons/wand.js:
lucide/dist/esm/icons/warehouse.js:
lucide/dist/esm/icons/washing-machine.js:
lucide/dist/esm/icons/watch.js:
lucide/dist/esm/icons/waves-ladder.js:
lucide/dist/esm/icons/waves.js:
lucide/dist/esm/icons/waypoints.js:
lucide/dist/esm/icons/webcam.js:
lucide/dist/esm/icons/webhook-off.js:
lucide/dist/esm/icons/webhook.js:
lucide/dist/esm/icons/weight.js:
lucide/dist/esm/icons/wheat-off.js:
lucide/dist/esm/icons/wheat.js:
lucide/dist/esm/icons/whole-word.js:
lucide/dist/esm/icons/wifi-high.js:
lucide/dist/esm/icons/wifi-low.js:
lucide/dist/esm/icons/wifi-off.js:
lucide/dist/esm/icons/wifi-pen.js:
lucide/dist/esm/icons/wifi-zero.js:
lucide/dist/esm/icons/wifi.js:
lucide/dist/esm/icons/wind-arrow-down.js:
lucide/dist/esm/icons/wind.js:
lucide/dist/esm/icons/wine-off.js:
lucide/dist/esm/icons/workflow.js:
lucide/dist/esm/icons/wine.js:
lucide/dist/esm/icons/worm.js:
lucide/dist/esm/icons/wrap-text.js:
lucide/dist/esm/icons/wrench.js:
lucide/dist/esm/icons/x.js:
lucide/dist/esm/icons/youtube.js:
lucide/dist/esm/icons/zap-off.js:
lucide/dist/esm/icons/zap.js:
lucide/dist/esm/icons/zoom-in.js:
lucide/dist/esm/icons/zoom-out.js:
lucide/dist/esm/iconsAndAliases.js:
lucide/dist/esm/lucide.js:
  (**
   * @license lucide v0.507.0 - ISC
   *
   * This source code is licensed under the ISC license.
   * See the LICENSE file in the root directory of this source tree.
   *)
*/
//# sourceMappingURL=lucide.js.map
