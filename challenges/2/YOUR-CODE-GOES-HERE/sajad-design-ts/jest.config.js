const {defaults} = require('jest-config');

/** @type {import('jest').Config} */
const config = {
  moduleFileExtensions: [...defaults.moduleFileExtensions, 'mts', 'cts'],
  moduleNameMapper: {
    '\\.(css|less)$': '<rootDir>/styleMock.js',
  },
  testEnvironment: 'jsdom',
};

module.exports = config;